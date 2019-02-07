<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Command;

use VirtMan\Command\Command;
use VirtMan\Storage\Storage;

/**
 * CreateStorage Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
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
     * @param string           $storageName Storage name
     * @param int              $size        Size
     * @param string           $type        Type
     * @param Libvirt Resource $connection  Connection resource
     * 
     * @return None
     */
    public function __construct(
        string $storageName,
        int $size,
        string $type,
        $connection
    ) {
        $this->_storageName = $storageName;
        $this->_size = $size;
        $this->_type = $type;
        $this->_baseStorageLocation = config('virtman.storageLocation');
        $this->_fullLocation = $this->_baseStorageLocation . '/' . $storageName 
                               . $size . 'MB' . $type;
        parent::__construct("create_storage", $connection);
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
                'name' => $this->storageName,
                'location' => $this->fullLocation,
                'type' => $this->type,
                'size' => $this->size,
                'active' => false,
                'initialized' => false,
            ]
        );
        return $this->storage;
    }
}
