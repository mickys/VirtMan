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
}
