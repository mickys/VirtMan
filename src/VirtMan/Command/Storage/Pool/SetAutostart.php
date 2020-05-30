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
 * SetAutostart Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class SetAutostart extends Command
{
    /**
     * SetAutostart Command
     *
     * @param Libvirt Storage Pool resource $resource 
     * @param int $mode 
     * 
     * @return None
     */
    public function __construct( $resource, int $mode )
    {
        parent::__construct("StoragePoolSetAutostart", $resource);
        $this->mode = $mode;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_storagepool_set_autostart($this->connection, $this->mode);
    }
}
