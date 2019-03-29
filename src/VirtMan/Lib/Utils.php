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
                ->update(['value' => \DB::raw($value)]);

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
        return ( 
            self::getConfig("can_run_job_from_host_queue") === "1" 
        );
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
     * Convert GB to Bytes
     *
     * @param int $gb 
     * 
     * @return int
     */
    public static function convertGBToBytes(int $gb)
    {
        return $gb * 1024 * 1024 * 1024; 
    }

    /**
     * Convert Bytes to GB
     *
     * @param int $bytes 
     * 
     * @return int
     */
    public static function convertBytesToGB(int $bytes)
    {
        return $bytes / 1024 / 1024 / 1024; 
    }
}
