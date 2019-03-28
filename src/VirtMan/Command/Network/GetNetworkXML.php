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
namespace VirtMan\Command\Network;

use VirtMan\Command\Command;

/**
 * GetNetworkXML Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetNetworkXML extends Command
{
    /**
     * GetNetworkXML Command
     *
     * @param Libvirt Connection $connection 
     * @param string             $name 
     * 
     * @return None
     */
    public function __construct( $connection, $name = "default" )
    {
        parent::__construct("GetNetworkXML", $connection);
        $this->name = $name;

    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_network_get_xml_desc(
            libvirt_network_get($this->connection, $this->name)
        );
    }
}
