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
namespace VirtMan\Command\Domain\Network;

use VirtMan\Command\Command;

/**
 * Interfaces Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetInterfaces extends Command
{
    /**
     * Interfaces Command
     *
     * @param Libvirt Domain Resource   $resource 
     * 
     * @return None
     */
    public function __construct( $resource )
    {
        parent::__construct("DomainNetworkInterfaces", $resource);
        $this->resource = $resource;

    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_get_interface_devices($this->resource);
    }
}
