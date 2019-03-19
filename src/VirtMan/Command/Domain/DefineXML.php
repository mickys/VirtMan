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
namespace VirtMan\Command\Domain;

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
     * @param Libvirt Connection $connection 
     * @param string             $xml 
     * 
     * @return None
     */
    public function __construct( $connection, $xml )
    {
        parent::__construct("domain_define_xml", $connection);
        $this->xml = $xml;

    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_define_xml($this->connection, $this->xml);
    }
}
