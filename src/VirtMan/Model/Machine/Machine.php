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
namespace VirtMan\Model\Machine;

use VirMan\Model\Group\Group;
use VirtMan\Model\Network\Network;
use VirtMan\Model\Storage\Storage;
use Illuminate\Database\Eloquent\Model;

/**
 * Machine Model
 *
 * @category VirtMan\Machine
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Machine extends Model
{
    /*
     * Machine Model:
     * int id
     * string name
     * string type
     * string status
     * string arch
     * int memory (mB)
     * int cpus
     * date started_at
     * date stopped_at
     * date timestamps
     */

    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_machines';

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var string
     */
    protected $fillable = [
        'name',
        'type',
        'status',
        'arch',
        'memory',
        'cpus',
        'ip',
        'node_id',
        'address',
        'package',
        'started_at',
        'stopped_at'
    ];

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
     * Machines Networks
     *
     * Get the networks a machine belongs to.
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
