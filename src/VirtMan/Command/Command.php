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

use VirtMan\Exceptions\NoLibvirtConnectionException;
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
     * @param string           $name                 Command name
     * @param Libvirt Resource $connectionOrResource Connection resource
     * 
     * @return None
     */
    protected function __construct(string $name, $connectionOrResource)
    {
        $this->name = $name;
        
        if (!$connectionOrResource) {
            throw new NoLibvirtConnectionException(
                "Attempting to create a " . $this->name . " " .
                "command without a Libvirt connection or resource.", 1
            );
        }

        $this->connection = $connectionOrResource;
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
