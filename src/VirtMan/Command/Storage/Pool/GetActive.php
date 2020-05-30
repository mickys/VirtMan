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
 * GetActive Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetActive extends Command
{
    /**
     * GetActive Command
     *
     * @param Libvirt Storage Pool resource $resource 
     * 
     * @return None
     */
    public function __construct( $resource )
    {
        parent::__construct("StoragePoolGetActive", $resource);
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_storagepool_is_active($this->connection);
    }
}
