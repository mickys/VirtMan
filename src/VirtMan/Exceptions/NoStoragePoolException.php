<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Exceptions;

use Exception;
use VirtMan\Model\Node\Node;

/**
 * No Storage Pool Exception
 *
 * @category VirtMan\Exceptions
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class NoStoragePoolException extends Exception
{
    /**
     * Node associated with the exception.
     *
     * @var Node
     */
    private $_node = null;

    /**
     * No Storage Exception
     *
     * Exception constructor.
     *
     * @param string    $message  Exception message
     * @param int       $code     Exception code 
     * @param Exception $previous Previous Exception
     * @param int       $node_id  Optional node id
     * 
     * @return None
     */
    public function __construct(
        string $message,
        int $code = 0,
        Exception $previous = null,
        int $node_id = null
    ) {
        if ($node_id) {
            $this->_node = Node::find($node_id);
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
        if ($this->_node) {
            $res.= ': ' . $this->_node->id . '\n';
        }
        return $res;
    }
}
