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
 * ListNetworks Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class ListNetworks extends Command
{
    public static $flags = [ 
        "ACTIVE" => VIR_NETWORKS_ACTIVE,
        "INACTIVE" => VIR_NETWORKS_INACTIVE,
        "ALL" => VIR_NETWORKS_ALL
    ];

    /**
     * Libvirt filter flag
     *
     * @var Filter int flag
     */
    private $_filter = null;

    /**
     * List Networks Command
     *
     * List Networks command constructor
     *
     * @param Libvirt Connection $connection Connection resource
     * @param int                $filter     Filter
     * 
     * @return None
     */
    public function __construct(
        $connection,
        int $filter = VIR_NETWORKS_ALL
    ) {
        parent::__construct("ListNetworks", $connection);
        $this->_filter = $filter;
    }

    /**
     * Get current Filter
     * 
     * @return int
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    /**
     * Set filter to ACTIVE networks
     * 
     * @return void
     */
    public function filterActive()
    {
        $this->_filter = $this->flags["ACTIVE"];
    }

    /**
     * Set filter to INACTIVE networks
     * 
     * @return void
     */
    public function filterInactive()
    {
        $this->_filter = $this->flags["INACTIVE"];
    }

    /**
     * Set filter to ALL available networks
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
     * Run the list networks command.
     *
     * @return libvirt network names array for the connection
     */
    public function run()
    {
        return libvirt_list_networks($this->connection, $this->_filter);
    }
}
