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
class Utils
{

    /**
     * Generate new unused Mac address
     *
     * @param string $hypervisor_name Hypervisor name
     * @param int    $seed            uintSeed
     * 
     * @return string
     */
    public static function generateRandomMacAddress(string $hypervisor_name, $seed=false)
    {
        if (!$seed) {
            $seed = 1;
        }

        if ($hypervisor_name == 'qemu') {
            $prefix = '52:54:00';
        } else {
            if ($hypervisor_name == 'xen') {
                $prefix = '00:16:3e';
            } else {
                $prefix = self::macbyte(($seed * rand()) % 256).':'.
                    self::macbyte(($seed * rand()) % 256).':'.
                    self::macbyte(($seed * rand()) % 256);
            }
        }
        return $prefix.':'.
            self::macbyte(($seed * rand()) % 256).':'.
            self::macbyte(($seed * rand()) % 256).':'.
            self::macbyte(($seed * rand()) % 256);
    }

    /**
     * Generate new unused Mac address
     *
     * @param int $val Value
     *
     * @return int
     */
    public static function macbyte(int $val)
    {
        if ($val < 16) {
            return '0'.dechex($val);
        }
        return dechex($val);
    }

    /**
     * Get configuration key values
     *
     * @param string $key Key
     *
     * @return string|array
     */
    public static function getConfig(string $key = "")
    {
        $result = [];

        if ($key !== "") {
            $virtman_config = \VirtMan\Model\Config\Config::where("name", $key)
                ->first();
            return $virtman_config["value"];
        } else {
            $virtman_config = \VirtMan\Model\Config\Config::all();
            foreach ($virtman_config as $data) {
                $result[$data["name"]] = $data["value"];
            }
        }

        return $result;
    }

    /**
     * Set configuration key values
     *
     * @param string $key   Key
     * @param string $value Value
     * 
     * @return void
     */
    public static function setConfig(string $key, $value)
    {
        $config = \VirtMan\Model\Config\Config::where("name", $key)->first();
        $data = [ "name" => $key, "value" => $value ];
        if (isset($config->name) && $config->name === $key) {
            // update
            \VirtMan\Model\Config\Config::where("name", $key)
                ->update(['value' => \DB::raw( $value )]);

        } else {
            // create
            $config = \VirtMan\Model\Config\Config::create($data)->save();
        }
    }

    /**
     * Can run job from host queue
     * 
     * @return bool
     */
    public static function canRunJobFromQHostQueue()
    {
        return ( self::getConfig("can_run_job_from_host_queue") === "1" || self::getConfig("can_run_job_from_host_queue") === null );
    }
    
    /**
     * Enable host queue jobs
     * 
     * @return bool
     */
    public static function enableRunJobFromQHostQueue()
    {
        return self::setConfig("can_run_job_from_host_queue", "1");
    }

    /**
     * Disable host queue jobs
     * 
     * @return bool
     */
    public static function disableRunJobFromQHostQueue()
    {
        return self::setConfig("can_run_job_from_host_queue", "0");
    }


    /**
     * Get Node Instance
     * 
     * @param int $id 
     * 
     * @return Virtman\Virtman
     */
    public static function getVirtManInstanceByNodeId(int $id)
    {
        return new \VirtMan\VirtMan(
            \VirtMan\Model\Node\Node::where(
                // get node running our dhcp server
                "id", $id
            )->first()->url
        );
    }
    

    /**
     * Get DHCP Node Instance
     * 
     * @return Virtman\Virtman
     */
    public static function getVirtManInstanceWithDHCP()
    {
        return self::getVirtManInstanceByNodeId(self::getConfig("dhcp_node_id"));
    }


    /**
     * Generate new unused Mac address
     *
     * @param string $hypervisor_name Hypervisor name
     *
     * @return string
     */
    public static function genMacAddress(string $hypervisor_name = "qemu")
    {
        $mac = self::generateRandomMacAddress($hypervisor_name);
        // check if it's unused
        $usedMac = \VirtMan\Model\Network\Network::where(
            ['mac' => $mac]
        )->first();
        
        if (isset($usedMac->id)) {
            $mac = self::genMacAddress($hypervisor_name);
        }
        return $mac;
    }

    /**
     * Generate the next ip Address 
     *
     * @param string $ip 
     * 
     * @return string
     */
    public static function getNextIpAfter(string $ip)
    {
        $long = ip2long($ip);
        $result = long2ip(++$long);
        if (!self::validaUsableIpv4Address($result)) {
            return self::getNextIpAfter($result);
        }
        return $result;
    }

    /**
     * Generate the next ip Address 
     *
     * @param string $ip 
     * 
     * @return string
     */
    public static function validaUsableIpv4Address(string $ip)
    {
        $str = explode(".", $ip);
        if (count($str) === 4) {
            if ($str[3] > 0 && $str[3] < 255) {
                return true;
            }
        } 
        return false;
    }

    /**
     * Generate the next container's ip Address
     *
     * @return array
     */
    public static function genNextAvailableContainerIpAddress()
    {
        $ipAddress = "";
        
        // see if we have containers, and if we do use the last one's ip
        $lastMachine = \VirtMan\Model\Machine\Machine::orderBy('id', 'desc')
            ->first();

        if (isset($lastMachine->ip)) {
            $ipAddress = $lastMachine->ip;

        } else {

            // else use the config start 
            $ipAddress = self::getConfig("container_ip_start");
        }

        return self::getNextIpAfter($ipAddress);        
    }

}
