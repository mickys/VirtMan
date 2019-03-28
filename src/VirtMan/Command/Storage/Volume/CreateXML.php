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
namespace VirtMan\Command\Storage\Volume;

use VirtMan\Command\Command;

/**
 * Create Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class CreateXML extends Command
{
    /**
     * Create Command
     *
     * @param Libvirt Connection $resource 
     * @param string             $xml 
     * 
     * @return None
     */
    public function __construct( $resource, $xml )
    {
        parent::__construct("DomainCreate", $resource);
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
        return libvirt_storagevolume_create_xml($this->connection, $this->xml);
    }
}
