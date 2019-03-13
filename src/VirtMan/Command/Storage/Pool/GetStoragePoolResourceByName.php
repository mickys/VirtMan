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
 * GetStoragePoolInfo Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class GetStoragePoolResourceByName extends Command
{
    /**
     * Get Storage Pool Info Command
     *
     * @param Libvirt Connection $resource Connection resource
     * @param string             $name     Name
     * 
     * @return None
     */
    public function __construct( $resource, $name )
    {
        parent::__construct("get_storage_pool_info", $resource);
        $this->name = $name;
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_storagepool_lookup_by_name($this->connection, $this->name);
    }
}
