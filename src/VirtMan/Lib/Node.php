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
     * Get the node resource multipliers
     *
     * @return array
     */

    public static function getResourceConfigs()
    {
        return [
            "cpus" => (int) Utils::getConfig("node_resource_multiplier_cpus"),
            "memory" => (int) Utils::getConfig("node_resource_multiplier_memory"),
            "storage" => (int) Utils::getConfig("node_resource_multiplier_storage"),
            "asids" => (int) Utils::getConfig("node_resource_multiplier_asids"),
        ];
    }
    

    /**
     * Get the number of containers that we can potentially store
     *
     * @param array $NodeResourceList 
     * 
     * @return array
     */

    public static function getRemainingContainerCountWithDetails($NodeResourceList = null)
    {
        if ($NodeResourceList === null) {
            $NodeResourceList = self::getNodeResourceList();
        }

        $container_needs = self::getContainerNeedsObject();

        $result = [
            "count" => 0,
            "lowest_resource" => [],
            "resources" => []
        ];

        foreach ($NodeResourceList as $node) {

            $res = [
                "cpus" => floor($node["free"]["cpus"] / $container_needs["cpus"]),
                "storage" => floor($node["free"]["storage"] / $container_needs["storage"]),
                "memory" => floor($node["free"]["memory"] / $container_needs["memory"]),
                "asids" => floor($node["free"]["asids"] / $container_needs["asids"]),
            ];

            asort($res);
            $lowestResource = array_keys($res)[0];
            $lowestValue = $res[$lowestResource];

            $result["lowest_resource"][] = [
                "node_id" => $node["node_id"],
                "resource" => (string) $lowestResource,
            ];
            $result["resources"][] = [
                "node_id" => $node["node_id"],
                "resource" => $res,
            ];
            $result["count"]+= $lowestValue;

        }
        return $result;
    }

    /**
     * Get container needs object
     *
     * @return array
     */

    public static function getContainerNeedsObject()
    {
        return [
            "storage" => (int) Utils::getConfig("container_storage_root_size_gb") +
                        (int) Utils::getConfig("container_storage_user_size_gb") +
                        (int) Utils::getConfig("container_storage_archive_size_gb"),
            "cpus" => (int) Utils::getConfig("container_vcpus"),
            "memory" => (int) Utils::getConfig("container_ram_in_mb") * 1024,
            "asids" => 1,
        ];
    }


    /**
     * Get nodes with resource counters
     *
     * @return array
     */

    public static function getNodesWithResourceCounters()
    {
        $results = [];
        $NodeResourceList = self::getNodeResourceList();
        $nodeList = \VirtMan\Model\Node\Node::get();
        foreach ($nodeList as $node) {
            $results[] = [
                "id" => $node->id,
                "name" => $node->name,
                "url" => $node->url,
                "real_resources" => [
                    "resource_vcpus" => $node->resource_vcpus,
                    "resource_memory" => $node->resource_memory,
                    "resource_storage" => $node->resource_storage,
                    "resource_asids" => 256,
                ],
                "resources" => $NodeResourceList[$node->id],
                "created_at" => $node->created_at,
                "status" => $node->status,
            ];
        }
        return $results;
    }

    /**
     * Get the next free node to place our container in
     *
     * @param array $NodeResourceList 
     * 
     * @return array
     */

    public static function getNextFreeNodeIDForNewContainer($NodeResourceList = null)
    {
        if ($NodeResourceList === null) {
            $NodeResourceList = self::getNodeResourceList();
        }

        $container_needs = self::getContainerNeedsObject();

        $found = false;

        foreach ($NodeResourceList as $node) {
            if(
                $node["free"]["cpus"] >= $container_needs["cpus"] &&
                $node["free"]["storage"] >= $container_needs["storage"] &&
                $node["free"]["memory"] >= $container_needs["memory"] &&
                $node["free"]["asids"] >= $container_needs["asids"]
            ) {
                $found = $node["node_id"];
                break;
            }
        }

        return $found;
    }

    /**
     * Get node resource list
     * 
     * @return array
     */
    public static function getNodeResourceList()
    {
        // Node Resources

        $multipliers = self::getResourceConfigs();

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

            $nodeDefaultResources = [
                "cpus" => $node->resource_vcpus * $multipliers["cpus"],
                "memory" => $node->resource_memory * $multipliers["memory"],
                "storage" => $node->resource_storage * $multipliers["storage"],
                "asids" => 256 * $multipliers["asids"],
            ];

            $nodeFreeResources = [
                "cpus" => $nodeDefaultResources["cpus"],
                "memory" => $nodeDefaultResources["memory"],
                "storage" => $nodeDefaultResources["storage"],
                "asids" => $nodeDefaultResources["asids"],
            ];

            $nodeResourceUsage = [
                "cpus" => 0,
                "memory" => 0,
                "storage" => 0,
                "asids" => 0,
            ];

            $archive_detached = 0;

            $machinesOnThisNode = \VirtMan\Model\Machine\Machine::where("node_id", "=", $node->id)->get();
            $machineCount = $machinesOnThisNode->count();

            if ($machineCount > 0) {

                foreach ($machinesOnThisNode as $container) {
                    
                    $nodeResourceUsage["memory"]+= $container->memory * 1024;
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

            $resourceList[$node->id] = [
                "node_id" => $node->id,
                "default" => $nodeDefaultResources,
                "used" => $nodeResourceUsage,
                "free" => $nodeFreeResources,
            ];
        }

        return $resourceList;
    }
}