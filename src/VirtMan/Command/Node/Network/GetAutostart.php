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
namespace VirtMan\Command\Node\Network;

use VirtMan\Command\Command;

/**
 * GetAutostart Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetAutostart extends Command
{
    /**
     * GetAutostart Command
     *
     * @param Libvirt Network Resource $resource 
     * 
     * @return None
     */
    public function __construct( $network )
    {
        parent::__construct("NodeNetworkGetAutostart", $network);
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_network_get_autostart($this->connection);
    }
}
