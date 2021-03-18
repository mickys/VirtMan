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
namespace VirtMan\Command\Domain\Network\Iface;

use VirtMan\Command\Command;

/**
 * Stats Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Stats extends Command
{
    /**
     * Stats Command
     *
     * @param Libvirt Domain Resource   $resource 
     * @param string                    $name 
     * 
     * @return None
     */
    public function __construct( $resource, $name )
    {
        parent::__construct("DomainNetworkInterfaceStats", $resource);
        $this->resource = $resource;
        $this->name = $name;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_interface_stats($this->resource, $this->name);
    }
}
