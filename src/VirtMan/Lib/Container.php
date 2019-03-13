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
 * VirtMan lib util class
 *
 * @category VirtMan\Lib
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Container
{

    /**
     * Generate the next container's settings
     *
     * @return array
     */
    public static function getNewContainerSettings()
    {
        $settings = array();

        $nodeInfo = self::getNodeForNewContainer();
        $settings["node_id"] = $nodeInfo["node_id"];
        $settings["node_instance"] = $nodeInfo["node_instance"];
        $settings["pool_resource"] = $nodeInfo["pool_resource"];
        $settings["mac_address"] = Utils::genMacAddress();
        $settings["ip_address"] = Utils::genNextAvailableContainerIpAddress();

        return $settings;
    }

    /**
     * Get Node id with enough resources for new container
     *
     * @return array
     */
    public static function getNodeForNewContainer()
    {
        $results = array();

        // check this node's space
        $Node = \VirtMan\Model\Node\Node::orderBy('id', 'desc')->first();

        $nodeInstance = Utils::getVirtManInstanceByNodeId($Node->id);

        $results["node_instance"] = $nodeInstance;

        if (self::nodeHasStoragePool($nodeInstance)) {

            $results["node_id"] = $Node->id;

            $containerStorageSize = Utils::convertGBToBytes(
                Utils::getConfig("container_storage_size_gb")
            );

            $spaceDetails = self::getNodeSpaceDetails($nodeInstance);

            $spaceLeft = $spaceDetails["capacity_max"] - $containerStorageSize;
            
            // is there enough space left on the node ?
            if (($spaceLeft) > 0 ) {

                $results["pool_resource"] = $spaceDetails["pool_resource"];
                
            } else {
                throw new \VirtMan\Exceptions\NoStorageSpaceException(
                    "Not enough storage space on selected node."
                );
            }

        } else {

            $poolName = Utils::getConfig("container_storage_pool_name");
            throw new \VirtMan\Exceptions\NoStoragePoolException(
                $poolName." not found on selected node."
            );

        }

        return $results;
    }

    /**
     * Get Node space details
     *
     * @param Virtman\Virtman $nodeInstance 
     * 
     * @return array
     */
    public static function getNodeSpaceDetails($nodeInstance)
    {
        $storagePoolResource = $nodeInstance->getStoragePoolResourceByName(
            Utils::getConfig("container_storage_pool_name")
        );

        $poolInfo = $nodeInstance->getStoragePoolInfo(
            $storagePoolResource
        );
        $poolInfo["capacity_over_allocation_percentage"] = Utils::getConfig("node_storage_over_allocation_percentage");

        $poolInfo["capacity_gb"] = Utils::convertBytesToGB($poolInfo["capacity"]);
        $poolInfo["allocation_gb"] = Utils::convertBytesToGB($poolInfo["allocation"]);
        $poolInfo["available_gb"] = Utils::convertBytesToGB($poolInfo["available"]);

        $poolInfo["capacity_max"] = $poolInfo["capacity"] *
            $poolInfo["capacity_over_allocation_percentage"];
        $poolInfo["capacity_max_gb"] = Utils::convertBytesToGB($poolInfo["capacity_max"]);
        
        $poolInfo["pool_resource"] = $storagePoolResource;

        return $poolInfo;
    }
    
    /**
     * Check if storage pool exists on node
     *
     * @param Virtman\Virtman $nodeInstance 
     * 
     * @return boolean
     */
    public static function nodeHasStoragePool($nodeInstance)
    {
        $storagePools = $nodeInstance->listStoragePools();
        $poolName = Utils::getConfig("container_storage_pool_name");
        return ( in_array($poolName, $storagePools));
    }
}