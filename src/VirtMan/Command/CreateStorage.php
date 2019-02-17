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

use VirtMan\Command\Command;
use VirtMan\Model\Storage\Storage;

/**
 * CreateStorage Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class CreateStorage extends Command
{
    /**
     * Storage Object name
     *
     * @var string
     */
    private $_storageName = "";

    /**
     * Initial directory where the storage image is located
     *
     * @var string
     */
    private $_baseStorageLocation = "";

    /**
     * Full path to the storage image
     *
     * @var string
     */
    private $_fullLocation = "";

    /**
     * Size for the storage image in MB.
     *
     * @var int
     */
    private $_size = null;

    /**
     * Boolean for whether the storage image is active.
     *
     * @var boolean active
     */
    private $_active = false;

    /**
     * Boolean for whether the storage image has been created.
     *
     * @var string
     */
    private $_initialized = false;

    /**
     * The Storage Image Type
     *
     * @var string
     */
    private $_type = "";

    /**
     * Created Storage Object
     *
     * @var Storage
     */
    private $_storage = null;

    /**
     * Create Storage
     *
     * Command constructor
     *
     * @param string           $storageName         Storage name
     * @param string           $baseStorageLocation Storage name
     * @param int              $size                Size
     * @param string           $type                Type
     * @param Libvirt Resource $connection          Connection resource
     * 
     * @return None
     */
    public function __construct(
        string $storageName,
        string $baseStorageLocation,
        int $size,
        string $type,
        $connection
    ) {
        $this->_storageName = $storageName;
        $this->_size = $size;
        $this->_type = $type;
        $this->_baseStorageLocation = $baseStorageLocation;
        
        $this->_fullLocation = $this->getFullLocation(
            $storageName,
            $baseStorageLocation,
            $size,
            $type
        );
        
        parent::__construct("CreateStorage", $connection);
    }

    /**
     * Get full location based on arguments
     *
     * Storage location on server
     *
     * @param string $storageName         Storage name
     * @param string $baseStorageLocation Storage name
     * @param int    $size                Size
     * @param string $type                Type
     * 
     * @return string
     */
    public function getFullLocation(
        string $storageName,
        string $baseStorageLocation,
        int $size,
        string $type
    ) {
        return $baseStorageLocation . DIRECTORY_SEPARATOR . $storageName 
               . "_" . $size . "_" . 'MB' . "_" . $type;
    }

    /**
     * Run
     *
     * Command Activation function
     *
     * @return Storage
     */
    public function run()
    {
        $this->storage = Storage::create(
            [
                'name'        => $this->_storageName,
                'location'    => $this->_fullLocation,
                'type'        => $this->_type,
                'size'        => $this->_size,
                'active'      => false,
                'initialized' => false,
            ]
        );
        return $this->storage;
    }
}
