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
     * @param int $node_id Node id
     * 
     * @return array
     */
    public static function getNewContainerSettings(string $name, int $node_id = 1)
    {
        $settings = array();

        $settings["node_id"] = $node_id;

        $settings["node_instance"] = new \VirtMan\VirtMan(
            \VirtMan\Model\Node\Node::where("id", '=', $node_id)->first()->url
        );

        $settings["pool_resource"] = $settings["node_instance"]
            ->getStoragePoolResourceByName(
                Utils::getConfig("container_storage_pool_name")
            );

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
                Utils::getConfig("container_storage_root_size_gb")
            ),
            "master" => Utils::getConfig("container_storage_root_template")
        ];

        $data["workdir"] = [
            "path" => $path."/".$name."_workdir.qcow2",
            "xml" => Volume::getQCOWImageXML(
                $name."_workdir.qcow2", 
                $path,
                Utils::getConfig("container_storage_workdir_template"),
                Utils::getConfig("container_storage_workdir_size_gb")
            ),
            "master" => Utils::getConfig("container_storage_workdir_template")
        ];

        $data["archive"] = [
            "path" => $path."/".$name."_archive.qcow2",
            "xml" => Volume::getQCOWImageXML(
                $name."_archive.qcow2", 
                $path,
                Utils::getConfig("container_storage_archive_template"),
                Utils::getConfig("container_storage_archive_size_gb")
            ),
            "master" => Utils::getConfig("container_storage_archive_template")
        ];

        return $data;
    }

    /**
     * Get Node id with enough resources for new container
     *
     * @return array
     */
    public static function getNodeForNewContainer($node_id)
    {
        $results = array();

        $nodeInstance = new \VirtMan\VirtMan(
            \VirtMan\Model\Node\Node::where("id", '=', $id)->first()->url
        );

        $results["node_instance"] = $nodeInstance;
        $results["node_id"] = $node_id;

        $containerStorageSize = 0;
        $containerStorageSize+= Utils::convertGBToBytes(
            Utils::getConfig("container_storage_root_size_gb")
        );

        $containerStorageSize+= Utils::convertGBToBytes(
            Utils::getConfig("container_storage_archive_size_gb")
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
        

        $XML = '<domain type="kvm">'.PHP_EOL;
        $XML.= '<name>'.$settings["name"].'</name>'.PHP_EOL;
        $XML.= '<metadata>'.PHP_EOL;
        $XML.= '  <libosinfo:libosinfo xmlns:libosinfo="http://libosinfo.org/xmlns/libvirt/domain/1.0">'.PHP_EOL;
        $XML.= '    <libosinfo:os id="http://centos.org/centos/7.0"/>'.PHP_EOL;
        $XML.= '  </libosinfo:libosinfo>'.PHP_EOL;
        $XML.= '</metadata>'.PHP_EOL;
        $XML.= '<memory>'.$ram.'</memory>'.PHP_EOL;
        $XML.= '<currentMemory>'.$ram.'</currentMemory>'.PHP_EOL;
        $XML.= '<vcpu>'.$cpus.'</vcpu>'.PHP_EOL;
        $XML.= '<os>'.PHP_EOL;
        $XML.= '  <type arch="x86_64" machine="q35">hvm</type>'.PHP_EOL;
        $XML.= '  <boot dev="hd"/>'.PHP_EOL;
        $XML.= ' </os>'.PHP_EOL;
        $XML.= '<features>'.PHP_EOL;
        $XML.= '  <acpi/>'.PHP_EOL;
        $XML.= '  <apic/>'.PHP_EOL;
        $XML.= '</features>'.PHP_EOL;
        $XML.= '<cpu mode="host-model"/>'.PHP_EOL;
        $XML.= '<clock offset="utc">'.PHP_EOL;
        $XML.= '  <timer name="rtc" tickpolicy="catchup"/>'.PHP_EOL;
        $XML.= '  <timer name="pit" tickpolicy="delay"/>'.PHP_EOL;
        $XML.= '  <timer name="hpet" present="no"/>'.PHP_EOL;
        $XML.= '</clock>'.PHP_EOL;
        $XML.= '<pm>'.PHP_EOL;
        $XML.= '  <suspend-to-mem enabled="no"/>'.PHP_EOL;
        $XML.= '  <suspend-to-disk enabled="no"/>'.PHP_EOL;
        $XML.= '</pm>'.PHP_EOL;
        $XML.= '<devices>'.PHP_EOL;

        // libvirt should find this by default, no need to specify it here
        // $XML.= '  <emulator>/usr/bin/qemu-system-x86_64</emulator>'.PHP_EOL;
        
        // disk drives

        $XML.= '  <disk type="file" device="disk">'.PHP_EOL;
        $XML.= '    <driver name="qemu" type="qcow2"/>'.PHP_EOL;
        $XML.= '    <source file="'.$settings["newContainer"]["disks"]["root"]["path"].'"/>'.PHP_EOL;
        $XML.= '    <target dev="vda" bus="virtio"/>'.PHP_EOL;
        $XML.= '  </disk>'.PHP_EOL;

        $XML.= '  <disk type="file" device="disk">'.PHP_EOL;
        $XML.= '    <driver name="qemu" type="qcow2"/>'.PHP_EOL;
        $XML.= '    <source file="'.$settings["newContainer"]["disks"]["workdir"]["path"].'"/>'.PHP_EOL;
        $XML.= '    <target dev="vdb" bus="virtio"/>'.PHP_EOL;
        $XML.= '  </disk>'.PHP_EOL;

        $XML.= '  <disk type="file" device="disk">'.PHP_EOL;
        $XML.= '    <driver name="qemu" type="qcow2"/>'.PHP_EOL;
        $XML.= '    <source file="'.$settings["newContainer"]["disks"]["archive"]["path"].'"/>'.PHP_EOL;
        $XML.= '    <target dev="vdc" bus="virtio"/>'.PHP_EOL;
        $XML.= '  </disk>'.PHP_EOL;

        $XML.= '  <controller type="usb" index="0" model="qemu-xhci" ports="15"/>'.PHP_EOL;

        // network
        $XML.= '  <interface type="bridge">'.PHP_EOL;
        $XML.= '    <source bridge="virbr0"/>'.PHP_EOL;
        $XML.= '    <mac address="'.$settings["newContainer"]["networking"]->mac.'"/>'.PHP_EOL;
        $XML.= '   <model type="virtio"/>'.PHP_EOL;
        $XML.= '  </interface>'.PHP_EOL;

        $XML.= '  <console type="pty"/>'.PHP_EOL;
        $XML.= '  <channel type="unix">'.PHP_EOL;
        $XML.= '   <source mode="bind"/>'.PHP_EOL;
        $XML.= '   <target type="virtio" name="org.qemu.guest_agent.0"/>'.PHP_EOL;
        $XML.= '  </channel>'.PHP_EOL;
        $XML.= '  <rng model="virtio">'.PHP_EOL;
        $XML.= '    <backend model="random">/dev/urandom</backend>'.PHP_EOL;
        $XML.= ' </rng>'.PHP_EOL;
        $XML.= '</devices>'.PHP_EOL;
        $XML.= '</domain>';

        return $XML;
    }


    /**
     * Get container overlay filesystem disk backed by template qcow2 image
     *
     * @param string $name 
     * @param string $path 
     * @param string $masterTemplate 
     * @param string $device 
     * 
     * @return string
     */
    public static function getDiskXML(string $name, string $path, string $masterTemplate, string $device) {

        $XML = '  <disk type="file" device="disk">'.PHP_EOL;
        $XML.= '    <driver name="qemu" type="qcow2" />'.PHP_EOL;
        $XML.= '    <source file="'.$path."/".$name.'"/>'.PHP_EOL;
        $XML.= '    <backingStore type="file" index="1">'.PHP_EOL;
        $XML.= '      <format type="qcow2" />'.PHP_EOL;
        $XML.= '      <source file="'.$masterTemplate.'" />'.PHP_EOL;
        $XML.= '      <backingStore />'.PHP_EOL;
        $XML.= '    </backingStore>'.PHP_EOL;
        $XML.= '    <target dev="'.$device.'" bus="virtio"/>'.PHP_EOL;
        $XML.= '  </disk>'.PHP_EOL;
       
        return $XML;
      }  

}