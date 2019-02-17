<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Pool
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Model\Storage;

use Illuminate\Database\Eloquent\Model;

// use VirtMan\Model\Storage\Volume;

/**
 * Pool Model
 *
 * @category VirtMan\Pool
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Pool extends Model
{
    /*
     * string type
     * string name
     * string devicePath nullable
     * string targetPath
     * boolean autostart
     * string hostName nullable
     * string formatType nullable
     * string adapter nullable
     * string authUsername nullable
     * string authType nullable
     * string secretUUID nullable
     * string permissionsMode nullable
     * string permissionsOwner nullable
     * string permissionsGroup nullable
     */

    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_pools';

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'devicePath',
        'targetPath',
        'autostart',
        'hostName',
        'formatType',
        'adapter',
        'authUsername',
        'authType',
        'secretUUID',
        'permissionsMode',
        'permissionsOwner',
        'permissionsGroup'
    ];

    /**
     * Initialize model
     * 
     * Create the storage pool with libvirt.
     *
     * @param TODO $value TODO
     * 
     * @return void
     */
    public function initialize($value='')
    {
        // TODO: Generate XML
        // TODO: pool = libvirt_storagepool_define_xml($res, $xml, $flags)
        // TODO: bool = libvirt_storagepool_create($pool);
    }

    /**
     * Delete model
     *
     * @param TODO $value TODO
     * 
     * @return void
     */
    public function delete($value='')
    {
        // TODO: destroy the pool
        // TODO: delete the pool
        // TODO: undefine the pool
    }
}
