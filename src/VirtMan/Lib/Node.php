<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 * 
 * @category VirtMan
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Lib;

/**
 * VirtMan lib container class
 *
 * @category VirtMan\Lib
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Node
{
    /**
     * Get the next free node
     *
     * @return array
     */

    public static function reserveResourcesOnFreeNodeAndGetID()
    {

    }

    public static function getNodeResourceList()
    {
        // Node Resources

        $multipliers = [
            "cpus" => (int) Utils::getConfig("node_resource_multiplier_cpus"),
            "memory" => (int) Utils::getConfig("node_resource_multiplier_memory"),
            "storage" => (int) Utils::getConfig("node_resource_multiplier_storage"),
            "asids" => (int) Utils::getConfig("node_resource_multiplier_asids"),
        ];

        // 1 - storage
        // each container has 3 disks ( root / archive / storage )
        // storage requirement can be calculated by adding up the 3 disks,
        // unless "archive" is detached, in which case we don't count it
        // - 1x node capacity 

        $storage_size_full = (int) Utils::getConfig("container_storage_root_size_gb") +
                    (int) Utils::getConfig("container_storage_user_size_gb") +
                    (int) Utils::getConfig("container_storage_archive_size_gb");

        $storage_size_archive_detached = (int) Utils::getConfig("container_storage_root_size_gb") +
                    (int) Utils::getConfig("container_storage_user_size_gb");

        // even if all users have their archive disks detached, we need to assume
        // some of them will eventually want to reattach them.. thus we want 
        // to have enough space to do so on the node..
        // so we keep at least 2 x archive space on them

        // node also needs work space to archive / migrate machines
        // but those go into the node own storage, not the pools for containers.

        // 2 - memory
        // - 2x node capacity 

        // 3 - cpus
        // - 2x node capacity 

        // 4 - ASIDS - max ~400
        // - 1x node capacity

        // based on container settings ( resources ), iterate over nodes 
        // and find the first one that can host our new container

        // @TODO: rewrite this as 1 SQL query maybe ?

        $resourceList = [];

        $nodeList = \VirtMan\Model\Node\Node::get();
        foreach ($nodeList as $node) {

            $nodeFreeResources = [
                "cpus" => $node->resource_vcpus * $multipliers["cpus"],
                "memory" => $node->resource_memory * $multipliers["memory"],
                "storage" => $node->resource_storage * $multipliers["storage"],
                "asids" => 256 * $multipliers["asids"],
            ];

            $nodeResourceUsage = [
                "cpus" => 0,
                "memory" => 0,
                "storage" => 0,
                "asids" => 0,
            ];

            $archive_detached = 0;

            $machinesOnThisNode = \VirtMan\Model\Machine\Machine::where("node_id", "=", $node->id);
            $machineCount = count($machinesOnThisNode);
            if ($machineCount > 0) {
                foreach ($machinesOnThisNode as $container) {
                    
                    $nodeResourceUsage["memory"]+= $container->memory;
                    $nodeResourceUsage["cpus"]+= $container->cpus;
                    $nodeResourceUsage["asids"]++;

                    if ($container->archive_detached == 1) {
                        $archive_detached++;
                        $nodeResourceUsage["storage"]+= $storage_size_archive_detached;
                    } else {
                        $nodeResourceUsage["storage"]+= $storage_size_full;
                    }
                }

                // if at least half containers have their archives detached 
                // add them back in so we can re attach some 

                $detached_archive_count = ceil($machineCount / 2);
                if($archive_detached >= $detached_archive_count ) {
                    $nodeResourceUsage["storage"]+= (int) Utils::getConfig("container_storage_archive_size_gb") * $detached_archive_count;
                }

                $nodeFreeResources["cpus"]-= $nodeResourceUsage["cpus"];
                $nodeFreeResources["memory"]-= $nodeResourceUsage["memory"];
                $nodeFreeResources["storage"]-= $nodeResourceUsage["storage"];
                $nodeFreeResources["asids"]-= $nodeResourceUsage["asids"];
            }

            $resourceList[$node->id] = $nodeFreeResources;
        }
    }
}