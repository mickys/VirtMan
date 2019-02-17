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

/**
 * Abstract Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
abstract class Command
{
    /**
     * Libvirt Connection Resource
     *
     * @var Libvirt Resource
     */
    protected $connection = null;

    /**
     *  Command Name
     *
     * @var string
     */
    protected $name = "";

    /**
     * Command
     *
     * Command constructor.
     *
     * @param string           $name       Command name
     * @param Libvirt Resource $connection Connection resource
     * 
     * @return None
     */
    protected function __construct(string $name, $connection)
    {
        $this->name = $name;
        
        if (!$connection) {
            throw new NoLibvirtConnectionException(
                "Attempting to create a " . $this->name . " " .
                "command without a Libvirt connection.", 1
            );
        }

        $this->connection = $connection;
    }

    /**
     * Get Connection resource
     *
     * Returns libvirt resource
     * 
     * @return libvirt resource
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get Command Name
     *
     * Returns Command Name string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Run
     *
     * Abstract Command action function.
     *
     * @return Mixed
     */
    abstract public function run();
}
