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
class DhcpItem extends Model
{
 
    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_dhcp';

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'mac',
        'name',
        'parent',
        //
        'node',
        'http_port',
        'tusd_port',
    ];

    public $timestamps = false; 

    /**
     * Parent Machine
     *
     * @return Has One Relationship
     */
    public function parent()
    {
        return $this->belongsTo('VirtMan\Model\Machine\Machine');
    }
}
