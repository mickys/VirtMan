<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 * 
 * @category VirtMan
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Lib;

/**
 * VirtMan lib container class
 *
 * @category VirtMan\Lib
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Node
{
    /**
     * Get the next free node
     *
     * @return array
     */

    public static function reserveResourcesOnFreeNodeAndGetID()
    {
        // Node Resources

        // 1 - storage
        // each container has 3 disks ( root / archive / storage )
        // storage requirement can be calculated by adding up the 3 disks,
        // unless "archive" is detached, in which case we don't count it
        // - 1x node capacity 

        // 2 - memory
        // - 2x node capacity 

        // 3 - cpus
        // - 2x node capacity 

        // 4 - ASIDS - max ~400
        // - 1x node capacity

        // based on container settings ( resources ), iterate over nodes 
        // and find the first one that can host our new container
        $nodeList = \VirtMan\Model\Node\Node::get();
        
    }
}