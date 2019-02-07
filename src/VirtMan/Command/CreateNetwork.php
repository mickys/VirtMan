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
use VirtMan\Exceptions\InvalidMacException;
use VirtMan\Exceptions\InvalidModelException;
use VirtMan\Network\Network;

/**
 * CreateNetwork Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class CreateNetwork extends Command
{
    /**
     * The Network MAC Address
     *
     * @var string
     */
    private $_mac = "";

    /**
     * The LibVirt "Network"
     *
     * @var string
     */
    private $_network = "";

    /**
     * The Network Model
     *
     * @var string
     */
    private $_model = "";

    /**
     * The created Network Object
     *
     * @var Network
     */
    private $_networkObject = null;

    /**
     * All of the available NIC models
     *
     * @var array string
     */
    private $_availableModels = null;

    /**
     * Create Network
     *
     * Create a Network Object
     *
     * @param string             $mac        Hardware Mac Address
     * @param string             $network    Object string
     * @param string             $model      Model string
     * @param Libvirt Connection $connection Connection resource
     * 
     * @return None
     */
    public function __construct(
        string $mac,
        string $network,
        string $model,
        $connection
    ) {
        parent::__construct("create_network", $connection);

        // Get rid of trailing new line
        if (config('virtman.connectionType') == "qemu") {
            $nics = libvirt_connect_get_nic_models($connection);
            $nics[count($nics) - 1] = substr_replace(
                $nics[count($nics) - 1], "", -1, 1
            );
            $this->availableModels = $nics;
        }

        if (!$this->_validateMac($mac)) {
            throw new InvalidMacException(
                "Attempting to create a network with an invalid MAC.", 1
            );
        }

        if (!$this->_validateModel($model)) {
            throw new InvalidModelException(
                "Attempting to create a network with an invalid NIC Model", 1
            );
        }

        $this->_mac = $mac;
        $this->_network = $network;
        $this->_model = $model;

    }

    /**
     * Run
     *
     * Run the create network command
     *
     * @return Network
     */
    public function run()
    {
        $this->networkObject = Network::create(
            [
                'mac' => $this->mac,
                'network' => $this->network,
                'model' => $this->model,
            ]
        );

        return $this->networkObject;
    }

    /**
     * Validate MAC
     *
     * Check if the MAC Address is valid
     *
     * @param string $mac Hardware Mac Address
     * 
     * @return boolean
     */
    private function _validateMac(string $mac)
    {
        // Based on IEEE MAC-48 standard
        return preg_match("/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/", $mac);
    }

    /**
     * Validate Model
     *
     * Check if the NIC Model is valid
     *
     * @param string $model NIC model string
     * 
     * @return boolean
     */
    private function _validateModel(string $model)
    {
        $notUsingQemu = config('virtman.connectionType') != "qemu";
        return $notUsingQemu || in_array($model, $this->availableModels);
    }
}
