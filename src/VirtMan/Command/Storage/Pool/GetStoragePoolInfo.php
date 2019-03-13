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
class GetStoragePoolInfo extends Command
{
    /**
     * List Storage Pools Command
     *
     * @param Libvirt Connection $connection Connection resource
     * 
     * @return None
     */
    public function __construct( $connection )
    {
        parent::__construct("get_storage_pool_info", $connection);
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_storagepool_get_info($this->connection);
    }
}
