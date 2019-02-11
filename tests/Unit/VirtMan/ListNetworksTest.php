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

use VirtMan\Command\ListNetworks;

/**
 * BaseTest
 *
 * @category VirtMan\Tests\Unit
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class ListNetworksTest extends UnitBaseTest
{
    /**
     * Test if connection property is set correctly
     * 
     * @return void
     */
    public function testSetConnectionProperty()
    {
        $command = new ListNetworks($this->connection);
        $this->assertEquals($this->connection, $command->getConnection());
    }

    /**
     * Test if command name property is set correctly
     * 
     * @return void
     */
    public function testSetCommandNameProperty()
    {
        $command = new ListNetworks($this->connection);
        $this->assertEquals("ListNetworks", $command->getName());
    }

    /**
     * Test if filters are properly set
     * 
     * @return void
     */
    public function testWhenConstructedFiltersAreProperlySet()
    {
        $filter = ListNetworks::$flags["ALL"];
        $command = new ListNetworks($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());

        $filter = ListNetworks::$flags["ACTIVE"];
        $command = new ListNetworks($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());

        $filter = ListNetworks::$flags["INACTIVE"];
        $command = new ListNetworks($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());
    }

    /**
     * Test if command returns expected output
     * 
     * @return void
     */
    public function testRunCommandWithInactiveFilteringShouldBeEmpty()
    {
        $command = new ListNetworks(
            $this->connection, ListNetworks::$flags["INACTIVE"]
        );
        $output = $command->run();
        $this->assertEquals([], $output);
    }
}