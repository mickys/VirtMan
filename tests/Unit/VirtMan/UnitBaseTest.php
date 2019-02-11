<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Tests\Unit
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Tests\Unit\VirtMan;

use VirtMan\VirtMan;

use PHPUnit\Framework\TestCase;

/**
 * BaseTest
 *
 * @category VirtMan\Tests\Unit
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
abstract class UnitBaseTest extends TestCase
{
    /**
     * Libvirt Connection Resource
     *
     * @var Libvirt Connection Resource
     */
    protected $connection = null;
    
    /**
     * Test Setup
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->connection = libvirt_connect(
            "test:///default",  // Using Test "mock" driver
            false,              // readonly false
            []                  // empty credentials
        );
        $this->assertTrue(true);
    }
}