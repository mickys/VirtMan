<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Machine
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Model\Node;

use Illuminate\Database\Eloquent\Model;

/**
 * Node Model
 *
 * @category VirtMan\Node
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Node extends Model
{
    /*
     * Node:
     * int id
     * string name
     * string url
     * string status
     * date sync_at
     * date created_at
     * date updated_at
     * 
     * string type
     * int resource_vcpus
     * int resource_memory
     * int resource_storage
     * string interface_0_name
     * string interface_0_mac
     * string interface_0_ip
     * string interface_1_name
     * string interface_1_mac
     * string interface_1_ip
     * string interface_1_netmask
     * string interface_1_dhcp_start
     * string interface_1_dhcp_end
     */

    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_nodes';

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var string
     */
    protected $fillable = [
        'name',
        'url',
        'status',
        'sync_at',
        'created_at',
        //
        'type',
        'resource_vcpus',
        'resource_memory',
        'resource_storage',
        'interface_0_name',
        'interface_0_mac',
        'interface_0_ip',
        'interface_1_name',
        'interface_1_mac',
        'interface_1_ip',
        'interface_1_netmask',
        'interface_1_dhcp_start',
        'interface_1_dhcp_end',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Machines Groups
     *
     * Get the groups a machine belongs to.
     *
     * @return Belongs To Relationship
     */
    public function groups()
    {
        return $this->belongsTo('VirtMan\Group\Group');
    }

    /**
     * Add Group
     *
     * Add a Group or array of groups to the machine.
     *
     * @param TODO $group TODO
     * 
     * @return TODO
     */
    public function addGroup(Group $group)
    {
        // TODO
    }

    /**
     * Machines Networks
     *
     * Get the networks a machine belongs to.
     *
     * @return Belongs To Many Relationship
     */
    public function networks()
    {
        return $this->belongsToMany('VirtMan\Network\Network');
    }

    /**
     * Machines Networks
     *
     * Get the networks a machine belongs to.
     *
     * @param TODO $network TODO
     * 
     * @return TODO
     */
    public function addNetworks($network)
    {
        // TODO
    }

    /**
     * Machines Networks
     *
     * Get the networks a machine belongs to.
     *
     * @return Has Many Relationship
     */
    public function storage()
    {
        return $this->hasMany('VirtMan\Storage\Storage');
    }

    /**
     * Add Machines Storage
     *
     * Add Storage
     *
     * @param TODO $storage TODO
     * 
     * @return TODO
     */
    public function addStorage($storage)
    {
        // TODO
    }

}