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

/**
 * BaseTest
 *
 * @category VirtMan\Tests\Unit
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class ListMachinesTest extends UnitBaseTest
{
    public $methodName = "ListMachines";
    public $methodFullName = "VirtMan\Command\ListMachines";

    /**
     * Test if connection property is set correctly
     * 
     * @return void
     */
    public function testSetConnectionProperty()
    {
        $command = new $this->methodFullName($this->connection);
        $this->assertEquals($this->connection, $command->getConnection());
    }

    /**
     * Test if command name property is set correctly
     * 
     * @return void
     */
    public function testSetCommandNameProperty()
    {
        $command = new $this->methodFullName($this->connection);
        $this->assertEquals($this->methodName, $command->getName());
    }

    /**
     * Test if filters are properly set
     * 
     * @return void
     */
    public function testWhenConstructedFiltersAreProperlySet()
    {
        $filter = $this->methodFullName::$flags["ALL"];
        $command = new $this->methodFullName($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());

        $filter = $this->methodFullName::$flags["ACTIVE"];
        $command = new $this->methodFullName($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());

        $filter = $this->methodFullName::$flags["INACTIVE"];
        $command = new $this->methodFullName($this->connection, $filter);
        $this->assertEquals($filter, $command->getFilter());
    }

    /**
     * Test if command returns expected output
     * 
     * @return void
     */
    public function testRunCommandWithInactiveFilteringShouldBeEmpty()
    {
        $command = new $this->methodFullName(
            $this->connection, $this->methodFullName::$flags["INACTIVE"]
        );
        $output = $command->run();
        $this->assertEquals([], $output);
    }

    /**
     * Test if command returns expected output
     * 
     * @return void
     */
    public function testRunCommandWithActiveFilteringShouldNotBeEmpty()
    {
        $command = new $this->methodFullName(
            $this->connection, $this->methodFullName::$flags["ACTIVE"]
        );
        $output = $command->run();
        $this->assertEquals(true, in_array("test", $output));
    }
    
}