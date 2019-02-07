<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Exceptions;

use Exception;
use VirtMan\Storage\Storage;

/**
 * Storage Already Active Exception
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class StorageAlreadyActiveException extends Exception
{
    /**
     * Storage associated with the exception
     *
     * @var Storage
     */
    private $_storage = null;

    /**
     * Storage Already Active Exception
     *
     * Exception constructor.
     *
     * @param string    $message    Exception message
     * @param int       $code       Exception code 
     * @param Exception $previous   Previous Exception
     * @param int       $storage_id Optional storage id
     * 
     * @return None
     */
    public function __construct(
        string $message,
        int $code = 0,
        Exception $previous = null,
        int $storage_id
    ) {
        $this->storage = Storage::find($storage_id);
        parent::__construct($message, $code, $previous);
    }

    /**
     * To string
     *
     * Generate a description of the exception.
     *
     * @return string
     */
    public function __tostring()
    {
        $res = __CLASS__ . ": [{$this->code}]: {$this->message}";
        $res.= ': ' . $this->_storage->id . '\n';
        return $res;
    }
}
