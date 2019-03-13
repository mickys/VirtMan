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
        $settings["node_id"] = self::getNodeForNewContainer();
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
        $settings = array();

        // check this node's 
        $Node = \VirtMan\Model\Node\Node::orderBy('id', 'desc')->first();

        if (self::nodeHasStoragePool($Node->id)) {

            $containerStorageSize = Utils::getConfig("container_storage_size_gb");
            $spaceDetails = self::getNodeSpaceDetails($Node->id);

            // print_r($spaceDetails);

        } else {
            
            $poolName = Utils::getConfig("container_storage_pool_name");
            throw new \VirtMan\Exceptions\NoStoragePoolException(
                $poolName." not found on selected node."
            );
        }

        return $settings;
    }

    /**
     * Get Node space details
     *
     * @param int $nodeId 
     * 
     * @return array
     */
    public static function getNodeSpaceDetails(int $nodeId)
    {
        $nodeInstance = Utils::getVirtManInstanceByNodeId($nodeId);
        $storagePools = $nodeInstance->listStoragePools();

        $poolName = Utils::getConfig("container_storage_pool_name");

    }
    
    /**
     * Check if storage pool exists on node
     *
     * @param int $nodeId 
     * 
     * @return boolean
     */
    public static function nodeHasStoragePool(int $nodeId)
    {
        $nodeInstance = Utils::getVirtManInstanceByNodeId($nodeId);
        $storagePools = $nodeInstance->listStoragePools();
        $poolName = Utils::getConfig("container_storage_pool_name");
        return ( in_array($poolName, $storagePools));
    }
}