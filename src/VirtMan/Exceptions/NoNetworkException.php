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
use VirtMan\Machine\Machine;
use VirtMan\Storage\Storage;
use VirtMan\Network\Network;

/**
 * No Network Exception
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class NoNetworkException extends Exception
{
    /**
     * Machine associated with the exception
     *
     * @var Machine
     */
    private $_machine = null;
    /**
     * Network associated with the exception
     *
     * @var Network
     */
    private $_network = null;

    /**
     * No Network Exception
     *
     * Exception constructor.
     *
     * @param string    $message    Exception message
     * @param int       $code       Exception code 
     * @param Exception $previous   Previous Exception
     * @param int       $machine_id Optional machine id
     * @param int       $network_id Optional network id
     * 
     * @return None
     */
    public function __construct(
        string $message,
        int $code = 0,
        Exception $previous = null,
        int $machine_id = null,
        int $network_id = null
    ) {
        if ($machine_id) {
            $this->_machine = Machine::find($machine_id)->first();
        }
        if ($network_id) {
            $this->_network = Network::find($network_id)->first();
        }
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
        if ($this->_machine) {
            $res.= ': ' . $this->_machine->id;
        }
        if ($this->_network) {
            $res.= ': ' . $this->_network->id . '\n';
        }
        return $res;
    }
}
