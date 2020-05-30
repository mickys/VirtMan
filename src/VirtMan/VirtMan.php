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
namespace VirtMan;

// Commands
use VirtMan\Command\CreateMachine;
use VirtMan\Command\CreateNetwork;
use VirtMan\Command\CreateStorage;


use VirtMan\Command\ListNetworks;
use VirtMan\Command\ListMachines;
use VirtMan\Command\ListNetworkCardModels;

// Storage Pools
use VirtMan\Command\Storage\Pool\ListStoragePools;
use VirtMan\Command\Storage\Pool\RefreshStoragePool;
use VirtMan\Command\Storage\Pool\GetStoragePoolInfo;
use VirtMan\Command\Storage\Pool\GetStoragePoolResourceByName;
use VirtMan\Command\Storage\Pool\DefineXML as StoragePoolDefineXML;
use VirtMan\Command\Storage\Pool\Create as StoragePoolCreate;
use VirtMan\Command\Storage\Pool\Destroy as StoragePoolDestroy;
use VirtMan\Command\Storage\Pool\GetActive as StoragePoolGetActive;
use VirtMan\Command\Storage\Pool\GetAutostart as StoragePoolGetAutostart;
use VirtMan\Command\Storage\Pool\SetAutostart as StoragePoolSetAutostart;


// Storage Volumes
use VirtMan\Command\Storage\Volume\CloneStorage;
use VirtMan\Command\Storage\Volume\CreateXML as StorageVolumeCreateXML;
use VirtMan\Command\Storage\Volume\GetByName as StorageVolumeGetByName;
use VirtMan\Command\Storage\Volume\Delete as StorageVolumeDelete;

// Network

use VirtMan\Command\Node\Network\Get as NodeNetworkGet;
use VirtMan\Command\Node\Network\GetXML as NodeNetworkGetXML;
use VirtMan\Command\Node\Network\DefineXML as NodeNetworkDefineXML;
use VirtMan\Command\Node\Network\Undefine as NodeNetworkUndefine;
use VirtMan\Command\Node\Network\GetActive as NodeNetworkGetActive;
use VirtMan\Command\Node\Network\SetActive as NodeNetworkSetActive;
use VirtMan\Command\Node\Network\GetAutostart as NodeNetworkGetAutostart;
use VirtMan\Command\Node\Network\SetAutostart as NodeNetworkSetAutostart;

// Domain
use VirtMan\Command\Domain\DefineXML as DomainDefineXML;
use VirtMan\Command\Domain\Lookup as DomainLookup;
use VirtMan\Command\Domain\Create as DomainCreate;
use VirtMan\Command\Domain\Destroy as DomainDestroy;
use VirtMan\Command\Domain\Undefine as DomainUndefine;
use VirtMan\Command\Domain\IsActive as DomainIsActive;
use VirtMan\Command\Domain\DefineXML as DomainGetXML;

// Exceptions
use VirtMan\Exceptions\ImpossibleMemoryAllocationException;
use VirtMan\Exceptions\ImpossibleStorageAllocationException;
use VirtMan\Exceptions\InvalidArchitectureException;

// Models
use VirtMan\Model\Group\Group;
use VirtMan\Model\Machine\Machine;
use VirtMan\Model\Network\Network;
use VirtMan\Model\Storage\Storage;

/**
 * VirtMan main class
 *
 * @category VirtMan
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class VirtMan
{
    /**
     * Library Version
     *
     * @var string
     */
    const VERSION = '0.2.0';

    /**
     * Libvirt Domain Connection
     *
     * @var Libvirt Connection
     */
    private $_connection = null;

    /**
     * Libvirt server User
     *
     * @var string
     */
    private $_authname = null;

    /**
     * Libvirt serrver Password
     *
     * @var string
     */
    private $_passphrase = null;

    /**
     * Maximum amount of memory for all machines
     *
     * @var int
     */
    private $_maxMemory = 0;

    /**
     * Maximum Storage Quota size
     *
     * @var int
     */
    private $_maxQuota = 0;

    /**
     * Array of all available Machine types
     *
     * @var string array
     */
    private $_machineTypes = [];

    /**
     * Arary of all supported Image types
     *
     * @var string array
     */
    private $_imageTypes = [
        'raw',
        'qcow',
        'qcow2',
    ];

    /**
     * VirtMan
     *
     * VirtMan Constructor
     * 
     * @param string $remoteUrl Libvirt machine URI
     *
     * @return
     */
    public function __construct( string $remoteUrl )
    {
        // Initialize Config Values
        // $this->_authname = config('virtman.username');
        // $this->_passphrase = config('virtman.password');
        // $this->_maxQuota = (int) config('virtman.storageQuota');
        // $this->_maxMemory = (int) config('virtman.memoryQuota');
        
        // Attempt to connect to LibVirt
        $this->_connection = $this->_connect($remoteUrl);
        
        // Initialize Environment Values
        $this->_machineTypes = $this->getMachineTypes();
    }

    /**
     * Libvirt is Installed
     *
     * Checks if the Libvirt PHP bindings are installed
     *
     * @return boolean
     */
    public function libvirtIsInstalled()
    {
        return function_exists('libvirt_version');
    }

    /**
     * Connect
     *
     * Authenticate with Libvirt and get the connection resource
     *
     * @param string $remoteUrl Libvirt machine URI
     * 
     * @return Libvirt Connection resource
     */
    private function _connect(string $remoteUrl)
    {
        return libvirt_connect($remoteUrl, false, []);
    }

    /**
     * Remaining Memory
     *
     * Amount of memory available for new machines
     *
     * @return int
     */
    public function remainingMemory()
    {
        $memUsed = 0;
        foreach (Machine::all() as $machine) {
            $memUsed += $machine->size;
        }
        return $this->_maxMemory - $memUsed;
    }

    /**
     * Remaining Storage Space
     *
     * Amount of storage space available for new Storage.
     *
     * @return int
     */
    public function remainingStorageSpace()
    {
        $storageUsed = 0;
        foreach (Storage::all() as $storage) {
            $storageUsed += $storage->size;
        }
        return $this->_maxQuota - $storageUsed;
    }

    /**
     * Get Machine Types
     *
     * Returns all of the available machine types.
     *
     * @return array
     */
    public function getMachineTypes()
    {
        // disabled for now.
        /*
        $keys = array_keys(libvirt_connect_get_machine_types($this->_connection));
        $types = [];
        foreach ($keys as $type) {
            // Remove trailing NULL character from each machine type
            array_push($types, substr_replace($type, "", -1, 1));
        }
        return $types;
        */

        /*
          libvirt_connect_get_machine_types seems to be failing in the 
          latest version, so we're using libvirt_connect_get_capabilities
          and parsing the xml to get these see libvirt.c line 2553
        
        $results = array();
        $xml = libvirt_connect_get_capabilities($this->_connection);

        $data = simplexml_load_string($xml);

        foreach ($data->guest as $guest) {
            foreach ($guest->arch->machine as $machine) {
                print_r($machine);
            }
        }

        return $xml;
        */
    }

    /**
     * Create Network
     *
     * Create a Network Object
     *
     * @param string $mac     Hardware Mac Address
     * @param string $network Network string
     * @param string $model   Network Card model
     * 
     * @return Network
     */
    public function createNetwork(
        string $mac, 
        string $network, 
        string $model = "e1000"
    ) {
        $command = new CreateNetwork($mac, $network, $model, $this->_connection);
        return $command->run();
    }

    /**
     * Create Storage
     *
     * Create a storage object
     *
     * @param string $name                Name
     * @param string $baseStorageLocation Storage name
     * @param string $type                Type
     * @param int    $size                Size
     * 
     * @return Storage
     */
    public function createStorage(
        string $name, 
        string $baseStorageLocation, 
        string $type, 
        int $size
    ) {
        /*
        if ($size < 0
            || $size > $this->_maxQuota
            || $size > $this->_remainingStorageSpace()
        ) {
            throw new ImpossibleStorageAllocationException(
                "Attempting to create storage with an impossible size", 1
            );
        }
        */

        $command = new CreateStorage(
            $name, $baseStorageLocation, $type, $size, $this->_connection
        );
        return $command->run();
    }

    /**
     * Create Machine
     *
     * Create a Virtual Machine
     *
     * @param string        $name    Machine name
     * @param string        $type    Machine type
     * @param int           $memory  Machine memory
     * @param int           $numCpus Machine cpus
     * @param string        $arch    Machine architecture
     * @param Storage array $storage Machine storage array
     * @param Network       $network Machine network object
     * @param int           $nodeId  Node ID
     * 
     * @return Machine
     */
    public function createMachine(
        string $name,
        string $type,
        int $memory,
        int $numCpus,
        string $arch,
        array $storage,
        Network $network,
        int $nodeId
    ) {

        /*
        if ($memory < 0 
            || $memory > $this->_maxMemory 
            || $memory > $this->_remainingMemory()
        ) {
            throw new ImpossibleMemoryAllocationException(
                "Attempting to create a machine with an impossible memory size.", 1
            );
        }
        
        
        if (!in_array($arch, $this->_machineTypes)) {
            throw new InvalidArchitectureException(
                "Attempting to create a machine with an unsupported Architecture",
                1, null, $arch
            );
        }
        */

        $command = new CreateMachine(
            $storage,
            $name,
            $type,
            $arch,
            $memory,
            $numCpus,
            $network,
            $nodeId,
            $this->_connection
        );
        return $command->run();
    }

    /**
     * List Networks
     *
     * Get Network list from connected node 
     *
     * @param int $filter VIR_NETWORKS_{ACTIVE|INACTIVE|ALL}
     * 
     * @return array
     */
    public function listNetworks(int $filter = null)
    {
        if ($filter === null) {
            $command = new ListNetworks($this->_connection);
        } else {
            $command = new ListNetworks($this->_connection, $filter);
        }
        return $command->run();
    }

    /**
     * List Machines
     *
     * Get Machines list from connected node 
     *
     * @param int $filter ALL = 1 | ACTIVE = 2 | INACTIVE = 3
     * 
     * @return array
     */
    public function listMachines(int $filter = 0)
    {
        if ($filter === 0) {
            $command = new ListMachines($this->_connection);
        } else {
            $command = new ListMachines($this->_connection, $filter);
        }
        return $command->run();
    }

    /**
     * List Storage Pools
     *
     * @return array
     */
    public function listStoragePools()
    {
        $command = new ListStoragePools($this->_connection);
        return $command->run();
    }

    /**
     * Get Storage Pool Resource
     *
     * @param string $name 
     * 
     * @return array
     */
    public function getStoragePoolResourceByName($name)
    {
        $command = new GetStoragePoolResourceByName($this->_connection, $name);
        return $command->run();
    }

    /**
     * Get Storage Pool Info
     *
     * @param resource $pool 
     * 
     * @return array
     */
    public function getStoragePoolInfo($pool)
    {
        $command = new RefreshStoragePool($pool);
        $command->run();

        $command = new GetStoragePoolInfo($pool);
        return $command->run();
    }
    
    /**
     * Refresh Storage Pool
     *
     * @param resource $pool 
     * 
     * @return array
     */
    public function refreshStoragePool($pool)
    {
        $command = new RefreshStoragePool($pool);
        return $command->run();
    }
    

    /**
     * Clone Storage Volume
     *
     * @param string $xml 
     * 
     * @return array
     */
    public function cloneStorageVolume($xml)
    {
        $command = new CloneStorage($this->_connection, $name);
        return $command->run();
    }

    /**
     * Create Storage Volume defined in XML
     *
     * @param VirtMan\Command\Domain\Lookup\resource $poolResource 
     * @param string                                 $xml 
     * 
     * @return string
     */
    public function createStorageVolume($poolResource, $xml)
    {
        $command = new StorageVolumeCreateXML($poolResource, $xml);
        return $command->run();
    }

    /**
     * Define a storage Pool
     *
     * @param string $xml 
     * 
     * @return resource
     */
    public function storagePoolDefineXML($xml)
    {
        $command = new StoragePoolDefineXML($this->_connection, $xml);
        return $command->run();
    }
    
    /**
     * Create a defined storage Pool
     *
     * @param string $xml 
     * 
     * @return string
     */
    public function storagePoolCreate($resource)
    {
        $command = new StoragePoolCreate($resource);
        return $command->run();
    }

    /**
     * Destroy a defined storage Pool
     *
     * @param resource $resource 
     * 
     * @return string
     */
    public function storagePoolDestroy($resource)
    {
        $command = new StoragePoolDestroy($resource);
        return $command->run();
    }

    /**
     * Get storage Pool active state
     *
     * @param resource $resource 
     * 
     * @return string
     */
    public function storagePoolGetActive($resource)
    {
        $command = new StoragePoolGetActive($resource);
        return $command->run();
    }
    
    /**
     * Get storage Pool Autostart
     *
     * @param resource $resource 
     * 
     * @return string
     */
    public function storagePoolGetAutostart($resource)
    {
        $command = new StoragePoolGetAutostart($resource);
        return $command->run();
    }
    
    /**
     * Set storage Pool Autostart
     *
     * @param resource $resource 
     * @param bool $mode 
     * 
     * @return string
     */
    public function storagePoolSetAutostart($resource, bool $mode)
    {
        $command = new StoragePoolSetAutostart($resource, (int) $mode);
        return $command->run();
    }

    /**
     * Get Network Resource
     *
     * @param string $name 
     * 
     * @return string
     */
    public function nodeNetworkGet(string $name = "default")
    {
        $command = new NodeNetworkGet($this->_connection, $name);
        return $command->run();
    }

    /**
     * Get Network XML
     *
     * @param resource $network 
     * 
     * @return string
     */
    public function nodeNetworkGetXML($network)
    {
        $command = new NodeNetworkGetXML($network);
        return $command->run();
    }

    /**
     * Define new network xml
     *
     * @param string $xml 
     * 
     * @return string
     */
    public function nodeNetworkDefineXML($xml)
    {
        $command = new NodeNetworkDefineXML($this->_connection, $xml);
        return $command->run();
    }

    /**
     * Undefine the specified domain resource
     *
     * @param resource $network 
     * 
     * @return string
     */
    public function nodeNetworkUndefine($network)
    {
        $command = new NodeNetworkUndefine($network);
        return $command->run();
    }   

    /**
     * Get network active state
     *
     * @param resource $network 
     * 
     * @return string
     */
    public function nodeNetworkGetActive($network)
    {
        $command = new NodeNetworkGetActive($network);
        return $command->run();
    }

    /**
     * Set network active state
     *
     * @param resource $network 
     * @param bool $mode 
     * 
     * @return string
     */
    public function nodeNetworkSetActive($network, bool $mode)
    {
        $command = new NodeNetworkSetActive($network, (int) $mode);
        return $command->run();
    }

    /**
     * Get network autostart
     *
     * @param resource $network 
     * 
     * @return string
     */
    public function nodeNetworkGetAutostart($network)
    {
        $command = new NodeNetworkGetAutostart($network);
        return $command->run();
    }

    /**
     * Set network autostart
     *
     * @param resource $network 
     * @param bool $mode 
     * 
     * @return string
     */
    public function nodeNetworkSetAutostart($network, bool $mode)
    {
        $command = new NodeNetworkSetAutostart($network, (int) $mode);
        return $command->run();
    }


    /**
     * Define new domain xml
     *
     * @param string $xml 
     * 
     * @return string
     */
    public function domainDefineXML($xml)
    {
        $command = new DomainDefineXML($this->_connection, $xml);
        return $command->run();
    }
    
    /**
     * Lookup domain by name
     *
     * @param string $name 
     * 
     * @return string
     */
    public function domainLookup($name)
    {
        $command = new DomainLookup($this->_connection, $name);
        return $command->run();
    }

    /**
     * Get domain xml by res
     *
     * @param string $name 
     * 
     * @return string
     */
    public function domainGetXML($resource)
    {
        $command = new DomainGetXML($resource);
        return $command->run();
    }
    

    /**
     * Is domain active
     *
     * @param resource $resource 
     * 
     * @return string
     */
    public function domainIsActive($resource)
    {
        $command = new DomainIsActive($resource);
        return $command->run();
    }

    /**
     * Create new domain using specified domain resource
     *
     * @param VirtMan\Command\Domain\Lookup\resource $domain 
     * 
     * @return string
     */
    public function domainCreate($domain)
    {
        $command = new DomainCreate($domain);
        return $command->run();
    }

    /**
     * Destroy the specified domain resource
     *
     * @param VirtMan\Command\Domain\Lookup\resource $domain 
     * 
     * @return string
     */
    public function domainDestroy($domain)
    {
        $command = new DomainDestroy($domain);
        return $command->run();
    }
    
    /**
     * Undefine the specified domain resource
     *
     * @param VirtMan\Command\Domain\Lookup\resource $domain 
     * 
     * @return string
     */
    public function domainUndefine($domain)
    {
        $command = new DomainUndefine($domain);
        return $command->run();
    }    

    /**
     * Get storage volume by name
     *
     * @param resource $pool 
     * @param string   $name 
     * 
     * @return string
     */
    public function storageVolumeGetByName($pool, $name)
    {
        $command = new StorageVolumeGetByName($pool, $name);
        return $command->run();
    }
    
    /**
     * Delete storage volume
     *
     * @param resource $resource 
     * 
     * @return string
     */
    public function storageVolumeDelete($resource)
    {
        $command = new StorageVolumeDelete($resource);
        return $command->run();
    }
    
    /**
     * Get connection
     *
     * @return Libvirt connection resource
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}
