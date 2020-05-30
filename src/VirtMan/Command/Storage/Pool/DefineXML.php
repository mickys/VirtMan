<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Command\Storage\Pool;

use VirtMan\Command\Command;

/**
 * DefineXML Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class DefineXML extends Command
{
    /**
     * DefineXML Command
     *
     * @param Libvirt Connection $resource 
     * @param string             $xml 
     * 
     * @return None
     */
    public function __construct( $resource, $xml )
    {
        parent::__construct("StoragePoolDefineXML", $resource);
        $this->xml = $xml;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        // resource / xml
        return libvirt_storagepool_define_xml($this->connection, $this->xml);
    }
}
