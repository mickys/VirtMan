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
 * Delete Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Delete extends Command
{
    /**
     * Delete Command
     *
     * @param Libvirt StoragePool $resource 
     * 
     * @return None
     */
    public function __construct( $resource )
    {
        parent::__construct("StorageVolumeDelete", $resource);
        $this->resource = $resource;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        // resource / name
        return libvirt_storagevolume_delete($this->resource);
    }
}
