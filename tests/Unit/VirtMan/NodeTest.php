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

use VirtMan\Model\Node\Node;

/**
 * BaseTest
 *
 * @category VirtMan\Tests\Unit
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class NodeTest extends UnitBaseTest
{

    /**
     * Test if connection property is set correctly
     * 
     * @return void
     */
    public function testSetConnectionProperty()
    {
        // setup
        // $app = $this->createApplication();

        /*
        // 2. condition
        $app->db->shouldReceive('installedMigrationsByDesc')->andReturn(collect());

        // 3. test
        $app->db->shouldReceive('makeLogTable')->once();
        $app->db->shouldReceive('doDowngrade')->never();
        */


        $mock = $this->createCustomMock('VirtMan\Model\Node\Node'); //->makePartial();
        $mock->shouldReceive('get')->once();
        // $mock->__construct();

        $nodes = $mock::get();

        /*
        foreach ($nodes as $n) {
            echo $n->name."\n";
        }
        */
        // $this->assertEquals($this->connection, $command->getConnection());
    }



}