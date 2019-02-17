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
class CreateStorageTest extends UnitBaseTest
{
    public $methodName = "CreateStorage";
    public $methodFullName = "VirtMan\Command\CreateStorage";

    public $storageName = "TestStorageName";
    public $baseStorageLocation = "/var/lib/libvirt/images";
    public $storageSize = -1;
    public $storageType = "ISO";

    /**
     * Instantiate command we're testing
     * 
     * @return Command
     */
    private function _createTestCommand() 
    {
        return new $this->methodFullName(
            $this->storageName,
            $this->baseStorageLocation,
            $this->storageSize,
            $this->storageType,
            $this->connection
        );
    }

    /**
     * Test if connection property is set correctly
     * 
     * @return void
     */
    public function testSetConnectionProperty()
    {
        $command = $this->_createTestCommand();
        $this->assertEquals($this->connection, $command->getConnection());
    }

    /**
     * Test if command name property is set correctly
     * 
     * @return void
     */
    public function testSetCommandNameProperty()
    {
        $command = $this->_createTestCommand();
        $this->assertEquals($this->methodName, $command->getName());
    }

    /**
     * Test if command returns expected output
     * 
     * @return void
     */
    public function testRunCommand()
    {
        $command = $this->_createTestCommand();

        $mock = $this->createCustomMock('VirtMan\Model\Node\Node'); //->makePartial();
        $mock->shouldReceive('get')->once();
        // $mock->__construct();

        $nodes = $mock::get();


        
        // print_r($this->app->config);
        // $output = $command->run();
        // $this->assertEquals(true, in_array("default", $output));
        // print_r($output);
    }

}