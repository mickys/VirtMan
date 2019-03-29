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
namespace VirtMan\Model\Config;

use Illuminate\Database\Eloquent\Model;

/**
 * Config Model
 *
 * @category VirtMan\Config
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Config extends Model
{
    /**
     * Migration Table
     *
     * @var string
     */
    protected $table = 'virtman_config';

    public $timestamps = false; 
    
    public $primaryKey = 'name';
    public $incrementing = false;

    /**
     * Array specifying which columns can be mass assignable
     *
     * @var string
     */
    protected $fillable = [
        'name',
        'value',
    ];

}