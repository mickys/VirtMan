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
 * VirtMan lib volume class
 *
 * @category VirtMan\Lib
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Volume
{

    /**
     * Get container overlay filesystem disk backed by template qcow2 image
     *
     * @param string $name 
     * @param string $path 
     * @param string $masterTemplate 
     * @param int    $capacityInGB 
     * 
     * @return string
     */
    public static function getQCOWImageXML(
        string $name, string $path, string $masterTemplate, int $capacityInGB = 5
    ) {

        $capacity = Utils::convertGBToBytes($capacityInGB);
        
        $XML = "<volume type='file'>
        <name>".$name."</name>
        <key>".$path."/".$name."</key>
        <source></source>
        <capacity unit='bytes'>".$capacity."</capacity>
        <target>
          <path>".$path."/".$name."</path>
          <format type='qcow2'/>
        </target>
        <backingStore>
          <path>".$masterTemplate."</path>
          <format type='qcow2'/>
        </backingStore>
      </volume>
        ";
        return $XML;
    }

    
}