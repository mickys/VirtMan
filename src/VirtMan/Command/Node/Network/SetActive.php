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
 * SetActive Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class SetActive extends Command
{
    /**
     * SetActive Command
     *
     * @param Libvirt Network Resource $resource 
     * @param int $mode 
     * 
     * @return None
     */
    public function __construct( $resource, int $mode )
    {
        parent::__construct("NodeNetworkSetActive", $resource);
        $this->mode = $mode;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_network_set_active($this->connection, $this->mode);
    }
}
