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

/**
 * Invalid Architecture Exception
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class InvalidArchitectureException extends Exception
{
    /**
     * The invalid Architecture
     *
     * @var string
     */
    private $_arch = "";

    /**
     * Invalid Architecture Exception
     *
     * Exception for unsupported Architectures.
     *
     * @param string    $message  Exception message
     * @param int       $code     Exception code
     * @param Exception $previous Previous Exception
     * @param string    $arch     Optional architecture string
     * 
     * @return None
     */
    public function __construct(
        string $message, 
        int $code,
        Exception $previous = null,
        string $arch = ""
    ) {
        $this->_arch = $arch;
        parent::__construct($message, $code, $previous);
    }

    /**
     * To String
     *
     * Get a string representation of the exception.
     * 
     * @return string
     */
    public function __tostring()
    {
        $res = __CLASS__ . ": [{$this->code}]: {$this->message}";
        $res.= ": {$this->_arch}\n";
        return $res;
    }
}
