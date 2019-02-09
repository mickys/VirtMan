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
namespace VirtMan\Command;

use VirtMan\Command\Command;

/**
 * ListMachines Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class ListMachines extends Command
{
    /**
     * Available filtering flags
     *
     * @var available flags
     */
    public static $flags = [ 
        "ALL" => 1,
        "ACTIVE" => 2,
        "INACTIVE" => 3,
    ];

    /**
     * Libvirt filter flag
     *
     * @var Filter int flag
     */
    private $_filter = null;

    /**
     * Libvirt Connection
     *
     * @var Libvirt Connection Resource
     */
    private $_connection = null;


    /**
     * List Machines Command
     *
     * List Machines command constructor
     *
     * @param Libvirt Connection $connection Connection resource
     * @param int                $filter     Filter
     * 
     * @return None
     */
    public function __construct(
        $connection,
        int $filter = 1
    ) {
        $this->_connection = $connection;
        $this->_filter = $filter;
    }

    /**
     * Set filter to ACTIVE machines
     * 
     * @return void
     */
    public function filterActive()
    {
        $this->_filter = $this->flags["ACTIVE"];
    }

    /**
     * Set filter to INACTIVE machines
     * 
     * @return void
     */
    public function filterInactive()
    {
        $this->_filter = $this->flags["INACTIVE"];
    }

    /**
     * Set filter to ALL available machines
     * 
     * @return void
     */
    public function filterAll()
    {
        $this->_filter = $this->flags["ALL"];
    }

    /**
     * Run
     *
     * Run the list machines command.
     *
     * @return libvirt machines names array for the connection
     */
    public function run()
    {
        if ($this->_filter === self::$flags["ALL"]) {
            return libvirt_list_domains($this->_connection);

        } else if ($this->_filter === self::$flags["ACTIVE"]) {
            return libvirt_list_active_domains($this->_connection);
            
        } else if ($this->_filter === self::$flags["INACTIVE"]) {
            return libvirt_list_inactive_domains($this->_connection);
        }

    }
}
