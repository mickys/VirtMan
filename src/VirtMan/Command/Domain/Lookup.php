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
namespace VirtMan\Command\Domain;

use VirtMan\Command\Command;

/**
 * Lookup Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Lookup extends Command
{
    /**
     * Lookup Command
     *
     * @param Libvirt Connection $connection 
     * @param string             $name 
     * 
     * @return None
     */
    public function __construct( $connection, $name )
    {
        parent::__construct("domain_lookup", $connection);
        $this->name = $name;

    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_lookup_by_name($this->connection, $this->name);
    }
}
