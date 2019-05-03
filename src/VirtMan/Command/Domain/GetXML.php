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
 * GetXML Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetXML extends Command
{
    /**
     * GetXML Command
     *
     * @param Libvirt Connection $connection 
     * @param string             $xml 
     * 
     * @return None
     */
    public function __construct( $connection, $xml )
    {
        parent::__construct("DomainGetXML", $connection);
        $this->xml = $xml;

    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_get_xml_desc($this->connection, $this->xml);
    }
}
