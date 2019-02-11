<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Tests\Features
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Tests\Features\VirtMan;

use VirtMan\VirtMan;

use PHPUnit\Framework\TestCase;

/**
 * BaseTest
 *
 * @category VirtMan\Tests\Features
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
abstract class FeaturesBaseTest extends TestCase
{
    /**
     * VirtMan Instance
     *
     * @var VirtMan Instance
     */
    protected $VirtMan = null;

    /**
     * Test Setup
     *
     * @return void
     */
    /*
    public function setUp()
    {
        parent::setUp();

        // Test "mock" driver = 'test:///default"'
        $this->VirtMan = new VirtMan(config('virtman.remoteUrl'));
        
        // $this->platform = getenv('PLATFORM');
        $this->assertTrue(true);
    }
    */
}