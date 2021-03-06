<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Group
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Model\Group;

use VirtMan\Model\Machine\Machine;
use Illuminate\Database\Eloquent\Model;

/**
 * Group Model
 *
 * @category VirtMan\Group
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Group extends Model
{
    /**
     * Migration table
     *
     * @var string
     */
    protected $table = 'virtman_groups';

    /**
     * Machines
     *
     * Machines in the Group.
     *
     * @return Has Many Relationship
     */
    public function machines()
    {
        return $this->hasMany('VirtMan\Model\Machine\Machine');
    }
}
