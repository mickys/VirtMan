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
 * Create Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Create extends Command
{
    /**
     * Create Command
     *
     * @param Libvirt Connection $domain 
     * 
     * @return None
     */
    public function __construct( $domain )
    {
        parent::__construct("DomainCreate", $domain);
    }

    /**
     * Run the command.
     *
     * @return array
     */
    public function run()
    {
        return libvirt_domain_create($this->connection);
    }
}
