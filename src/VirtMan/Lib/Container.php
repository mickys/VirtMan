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
class Container
{

    /**
     * Generate the next container's settings
     *
     * @param string $name Container name
     * 
     * @return array
     */
    public static function getNewContainerSettings(string $name)
    {
        $settings = array();

        $nodeInfo = self::getNodeForNewContainer();
        $settings["node_id"] = $nodeInfo["node_id"];
        $settings["node_instance"] = $nodeInfo["node_instance"];
        $settings["pool_resource"] = $nodeInfo["pool_resource"];
        $settings["disks"] = self::getNewContainerDisks($name);

        return $settings;
    }

    /**
     * Get new container's disks
     *
     * @param string $name Container name
     * 
     * @return array
     */
    public static function getNewContainerDisks(string $name)
    {
        $path = Utils::getConfig("storage_location");

        $data = [];
        $data["root"] = [
            "path" => $path."/".$name."_root.qcow2",
            "xml" => Volume::getQCOWImageXML(
                $name."_root.qcow2",
                $path,
                Utils::getConfig("container_storage_root_template"),
                10
            )
        ];
        $data["user"] = [
            "path" => $path."/".$name."_storage.qcow2",
            "xml" => Volume::getQCOWImageXML(
                $name."_storage.qcow2", 
                $path,
                Utils::getConfig("container_storage_user_template"),
                200
            )
        ];

        return $data;
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

            $containerStorageSize = 0;
            $containerStorageSize+= Utils::convertGBToBytes(
                Utils::getConfig("container_storage_root_size_gb")
            );

            $containerStorageSize+= Utils::convertGBToBytes(
                Utils::getConfig("container_storage_user_size_gb")
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
        $poolInfo["capacity_over_allocation_percentage"] = Utils::getConfig(
            "node_storage_over_allocation_percentage"
        );

        $poolInfo["capacity_gb"] = Utils::convertBytesToGB($poolInfo["capacity"]);
        $poolInfo["allocation_gb"] = Utils::convertBytesToGB(
            $poolInfo["allocation"]
        );
        $poolInfo["available_gb"] = Utils::convertBytesToGB($poolInfo["available"]);

        $poolInfo["capacity_max"] = $poolInfo["capacity"] *
            $poolInfo["capacity_over_allocation_percentage"];
        $poolInfo["capacity_max_gb"] = Utils::convertBytesToGB(
            $poolInfo["capacity_max"]
        );
        
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

    /**
     * Get Final Machine XML
     *
     * @param array $settings  
     * 
     * @return boolean
     */
    public static function getNewMachineXML($settings)
    {
       
        $ram = Utils::getConfig("container_ram_in_mb") * 1024;
        $cpus = Utils::getConfig("container_vcpus");
        

        $XML = '<domain type="kvm">'."\n";
        $XML.= '<name>'.$settings["name"].'</name>'."\n";
        $XML.= '<metadata>'."\n";
        $XML.= '  <libosinfo:libosinfo xmlns:libosinfo="http://libosinfo.org/xmlns/libvirt/domain/1.0">'."\n";
        $XML.= '    <libosinfo:os id="http://centos.org/centos/7.0"/>'."\n";
        $XML.= '  </libosinfo:libosinfo>'."\n";
        $XML.= '</metadata>'."\n";
        $XML.= '<memory>'.$ram.'</memory>'."\n";
        $XML.= '<currentMemory>'.$ram.'</currentMemory>'."\n";
        $XML.= '<vcpu>'.$cpus.'</vcpu>'."\n";
        $XML.= '<os>'."\n";
        $XML.= '  <type arch="x86_64" machine="q35">hvm</type>'."\n";
        $XML.= '  <boot dev="hd"/>'."\n";
        $XML.= ' </os>'."\n";
        $XML.= '<features>'."\n";
        $XML.= '  <acpi/>'."\n";
        $XML.= '  <apic/>'."\n";
        $XML.= '</features>'."\n";
        $XML.= '<cpu mode="host-model"/>'."\n";
        $XML.= '<clock offset="utc">'."\n";
        $XML.= '  <timer name="rtc" tickpolicy="catchup"/>'."\n";
        $XML.= '  <timer name="pit" tickpolicy="delay"/>'."\n";
        $XML.= '  <timer name="hpet" present="no"/>'."\n";
        $XML.= '</clock>'."\n";
        $XML.= '<pm>'."\n";
        $XML.= '  <suspend-to-mem enabled="no"/>'."\n";
        $XML.= '  <suspend-to-disk enabled="no"/>'."\n";
        $XML.= '</pm>'."\n";
        $XML.= '<devices>'."\n";
        $XML.= '  <emulator>/usr/bin/qemu-system-x86_64</emulator>'."\n";
        
        // disk drives
        $XML.= '  <disk type="file" device="disk">'."\n";
        $XML.= '    <driver name="qemu" type="qcow2"/>'."\n";
        $XML.= '    <source file="'.$settings["newContainer"]["disks"]["root"]["path"].'"/>'."\n";
        $XML.= '    <target dev="vda" bus="virtio"/>'."\n";
        $XML.= '  </disk>'."\n";

        $XML.= '  <disk type="file" device="disk">'."\n";
        $XML.= '    <driver name="qemu" type="qcow2"/>'."\n";
        $XML.= '    <source file="'.$settings["newContainer"]["disks"]["user"]["path"].'"/>'."\n";
        $XML.= '    <target dev="vdb" bus="virtio"/>'."\n";
        $XML.= '  </disk>'."\n";

        $XML.= '  <controller type="usb" index="0" model="qemu-xhci" ports="15"/>'."\n";

        // network
        $XML.= '  <interface type="bridge">'."\n";
        $XML.= '    <source bridge="virbr0"/>'."\n";
        $XML.= '    <mac address="'.$settings["networking"]->mac.'"/>'."\n";
        $XML.= '   <model type="virtio"/>'."\n";
        $XML.= '  </interface>'."\n";

        $XML.= '  <console type="pty"/>'."\n";
        $XML.= '  <channel type="unix">'."\n";
        $XML.= '   <source mode="bind"/>'."\n";
        $XML.= '   <target type="virtio" name="org.qemu.guest_agent.0"/>'."\n";
        $XML.= '  </channel>'."\n";
        $XML.= '  <rng model="virtio">'."\n";
        $XML.= '    <backend model="random">/dev/urandom</backend>'."\n";
        $XML.= ' </rng>'."\n";
        $XML.= '</devices>'."\n";
        $XML.= '</domain>';

        return $XML;
    }

}