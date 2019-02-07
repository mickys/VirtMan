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
use VirtMan\Exceptions\NoNetworkException;
use VirtMan\Exceptions\NoStorageException;
use VirtMan\Exceptions\StorageAlreadyActiveException;
use VirtMan\Machine\Machine;
use VirtMan\Network\Network;
use VirtMan\Storage\Storage;

/**
 * CreateMachine Command
 *
 * @category VirtMan\Command
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class CreateMachine extends Command
{
    /**
     * Created Machine
     *
     * @var Machine
     */
    private $_machine = null;

    /**
     * Storage for the Created Machine
     *
     * @var Storage
     */
    private $_storage = null;

    /**
     * Created Machine Name
     *
     * @var string
     */
    private $_machineName = null;

    /**
     * Created Machine Type
     *
     * @var string
     */
    private $_type = null;

    /**
     * Created Machine Architecture
     *
     * @var string
     */
    private $_arch = null;

    /**
     * Created Machine Memory Size
     *
     * @var string
     */
    private $_memory = null;

    /**
     * Created Machine number of vcpus cores
     *
     * @var ints
     */
    private $_cpus = null;

    /**
     * Created Machine Network
     *
     * @var Network
     */
    private $_network = null;

    /**
     * Libvirt resource from Machine creation
     *
     * @var Libvirt Resource
     */
    private $_resource = null;

    /**
     * Create Machine Command
     *
     * Create Machine command constructor
     *
     * @param Storage array      $storage    Storage Array
     * @param string             $name       Machine name
     * @param string             $type       Machine type
     * @param string             $arch       Architecture
     * @param int                $memory     Number of MB
     * @param int                $cpus       Number of CPUS
     * @param Network            $network    Network
     * @param Libvirt Connection $connection Connection resource
     * 
     * @return None
     */
    public function __construct(
        array $storage,
        string $name,
        string $type,
        string $arch,
        int $memory,
        int $cpus,
        Network $network,
        $connection
    ) {

        if (empty($storage)) {
            throw new NoStorageException(
                "Attempting to create a machine with no storage.", 1
            );
        }

        if (!$network) {
            throw new NoNetworkException(
                "Attempting to create a machine with no network.", 1
            );
        }

        parent::__construct("create_machine", $connection);

        $this->_arch = $arch;
        $this->_memory = $memory;
        $this->_cpus = $cpus;
        $this->_conn = $connection;
        $this->_storage = $storage;
        $this->_network = $network;

        $this->_type = ($type) ? $type : "nix";
        $this->_machineName = ($name) ? $name : _generateMachineName($this->_type);
    }

    /**
     * Run
     *
     * Run the create machine command.
     *
     * @return Machine
     */
    public function run()
    {
        $this->_machine = Machine::create(
            [
                'name'       => $this->_machineName,
                'type'       => $this->_type,
                'status'     => 'installing',
                'arch'       => $this->_arch,
                'memory'     => $this->_memory,
                'cpus'       => $this->_cpus,
                'started_at' => null,
                'stopped_at' => null,
            ]
        );
        $this->_machine->addStorage($this->_storage);
        $this->_machine->addNetworks($this->_network);
        $this->_resource = $this->_createMachine();

        return $this->_machine;
    }

    /**
     * Generate Machine Name
     *
     * Generate a Machine name given the Machine's type.
     *
     * @param string $type Machine type
     *
     * @return string
     */
    private function _generateMachineName(string $type)
    {
        return $type . "Machine" . (Machine::where('type', $type)->count() + 1);
    }

    /**
     * Create Machine
     *
     * Create a libvirt virtual machine.
     *
     * @return Libvirt Resource
     */
    private function _createMachine()
    {
        $iso = $this->_getIsoImage();
        $disks = $this->_getDisks();
        $networkCard = $this->_getNetworkCard();
        return libvirt_domain_new(
            $this->_conn, 
            $this->_machineName,
            $this->_arch,
            $this->_memory,
            $this->_memory,
            $this->_cpus,
            $iso,
            $disks,
            $networkCard
        );
    }

    /**
     * Get ISO Image
     *
     * Get the instalation image for the Machine.
     *
     * @return string
     */
    private function _getIsoImage()
    {
        return $this->_storage[0]->location;
    }

    /**
     * Get Disks
     *
     * Create Libvirt Storage Images for the Machine.
     *
     * @return Libvirt Image array
     */
    private function _getDisks()
    {
        $disks = [];
        for ($i = 1; $i < count($this->_storage); $i++) {
            $s = $this->_storage[i];
            if ($s->active) {
                throw new StorageAlreadyActiveException(
                    "Attempting to reactivate a storage volume.", 1, null, $s->id
                );
            }

            if (!$s->initialized) {
                $s->initialize($this->conn);
            }

            array_push(
                $disks,
                libvirt_storagevolume_lookup_by_name(
                    $this->_conn,
                    $s->name
                )
            );
            $s->active = true;
            $s->save();
        }
        return $disks;
    }

    /**
     * Get Network Card
     *
     * Get the Network Card information for the Machine.
     *
     * @return string array
     */
    private function _getNetworkCard()
    {
        $networkCard = [
            "mac" => $this->_network->mac,
            "network" => $this->_network->network,
            "model" => $this->_network->model,
        ];
        return $networkCard;
    }

}
