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
        string $name, string $path, string $masterTemplate = null, int $capacityInGB = 5
    ) {

        $capacity = Utils::convertGBToBytes($capacityInGB);
        
        $XML = "<volume type='file'>" . PHP_EOL;
        $XML.= "  <name>".$name."</name>" . PHP_EOL;
        $XML.= "  <key>".$path."/".$name."</key>" . PHP_EOL;
        $XML.= "  <source></source>" . PHP_EOL;
        $XML.= "  <capacity unit='bytes'>".$capacity."</capacity>" . PHP_EOL;
        $XML.= "  <target>" . PHP_EOL;
        $XML.= "    <path>".$path."/".$name."</path>" . PHP_EOL;
        $XML.= "    <format type='qcow2'/>" . PHP_EOL;
        $XML.= "  </target>".PHP_EOL;
        if ($masterTemplate !== null) {
            $XML.= "  <backingStore>" . PHP_EOL;
            $XML.= "    <path>".$masterTemplate."</path>" . PHP_EOL;
            $XML.= "    <format type='qcow2'/>" . PHP_EOL;
            $XML.= "  </backingStore>" . PHP_EOL;
        }
        $XML.= "</volume>";
        return $XML;
    } 


    /**
     * Get a base empty filesystem disk image
     *
     * @param string $name 
     * @param string $path 
     * @param int    $capacityInGB 
     * 
     * @return string
     */
    public static function getQCOWBaseImageXML(string $name, int $capacityInGB = 5) {

        $capacity = Utils::convertGBToBytes($capacityInGB);
        
        $XML = "<volume type='file'>" . PHP_EOL;
        $XML.= "  <name>".$name."</name>" . PHP_EOL;
        $XML.= "  <capacity unit='bytes'>".$capacity."</capacity>" . PHP_EOL;
        $XML.= "  <target>" . PHP_EOL;
        $XML.= "    <format type='qcow2'/>" . PHP_EOL;
        $XML.= "    <features>" . PHP_EOL;
        $XML.= "      <lazy_refcounts/>" . PHP_EOL;
        $XML.= "    </features>" . PHP_EOL;
        $XML.= "  </target>".PHP_EOL;
        $XML.= "</volume>";
        return $XML;
    } 
}