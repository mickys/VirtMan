<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Network
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Model\Network;

use VirtMan\Model\Machine\Machine;
use Illuminate\Database\Eloquent\Model;

/**
 * Network Model
 *
 * @category VirtMan\Network
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Network extends Model
{
    /*
     *
     * int id
     * string mac
     * string network
     * string model
     *
     */

    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_networks';

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'mac',
        'network',
        'model'
    ];

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
