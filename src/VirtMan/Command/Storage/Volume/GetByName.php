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
 * GetByName Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetByName extends Command
{
    /**
     * GetByName Command
     *
     * @param Libvirt StoragePool $resource 
     * @param string              $name 
     * 
     * @return None
     */
    public function __construct( $resource, $name )
    {
        parent::__construct("StorageVolumeGetByName", $resource);
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
        // resource / name
        return libvirt_storagevolume_lookup_by_name($this->resource, $this->name);
    }
}
