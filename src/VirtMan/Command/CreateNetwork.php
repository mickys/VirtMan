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
use VirtMan\Exceptions\InvalidMacException;
use VirtMan\Exceptions\InvalidModelException;
use VirtMan\Model\Network\Network;

/**
 * CreateNetwork Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
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
    private $_availableModels = array(
        'e1000','e1000-82544gc', 'e1000-82545em','e1000e',
        'i82550','i82551','i82557a','i82557b', 'i82557c',
        'i82558a','i82558b','i82559a','i82559b','i82559c',
        'i82559er','i82562','i82801','ne2k_pci','pcnet',
        'rocker','rtl8139','virtio-net-pci','vmxnet3'
    );

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
        parent::__construct("CreateNetwork", $connection);

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
                'mac' => $this->_mac,
                'network' => $this->_network,
                'model' => $this->_model,
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
        return in_array($model, $this->_availableModels);
    }
}
