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
 * VirtMan lib network class
 *
 * @category VirtMan\Lib
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class Network
{
    /**
     * Get network DHCP XML
     *
     * @return string
     */
    public static function getNetworkDHCPXML()
    {
        $XML = '<network>'."\n";
        $XML.= '    <name>default</name>'."\n";
        $XML.= '    <uuid>a522aa01-9846-497e-85b6-0407efb95693</uuid>'."\n";
        // $XML.= '    <forward mode="nat">'."\n";
        // $XML.= '        <nat>'."\n";
        // $XML.= '            <port start="1024" end="65535"/>'."\n";
        // $XML.= '        </nat>'."\n";
        // $XML.= '    </forward>'."\n";
        $XML.= '    <forward mode="route" dev="eth0" />'."\n";
        $XML.= '        <interface dev="eth0" />'."\n";
        $XML.= '    </forward>'."\n";
        $XML.= '    <bridge name="virbr0" stp="on" delay="0"/>'."\n";
        $XML.= '    <mac address="52:54:00:61:a8:8f"/>'."\n";
        $XML.= '    <ip address="192.168.122.1" netmask="255.255.255.0" localPtr="yes">'."\n";
        $XML.= '        <dhcp>'."\n";
        $XML.= '            <range start="192.168.122.10" end="192.168.122.100"/>'."\n";

        $items = \VirtMan\Model\Network\DhcpItem::all();
        $i = 0;
        foreach ($items as $item) {
            $XML.= '            <host mac="'.$item->mac.'" name="Container_'.++$i.'" ip="'.$item->ip.'"/>'."\n";
        }
        $XML.= '        </dhcp>'."\n";
        $XML.= '    </ip>'."\n";
        $XML.= '</network>'."\n";

        return $XML;
    }

    /**
     * Generate new unused Mac address
     *
     * @param string $hypervisor_name Hypervisor name
     * @param int    $seed            uintSeed
     * 
     * @return string
     */
    public static function generateRandomMacAddress(
        string $hypervisor_name, $seed=false
    ) {

        if (!$seed) {
            $seed = 1;
        }

        if ($hypervisor_name == 'qemu') {
            $prefix = '52:54:00';
        } else {
            if ($hypervisor_name == 'xen') {
                $prefix = '00:16:3e';
            } else {
                $prefix = self::macbyte(($seed * rand()) % 256).':'.
                    self::macbyte(($seed * rand()) % 256).':'.
                    self::macbyte(($seed * rand()) % 256);
            }
        }
        return $prefix.':'.
            self::macbyte(($seed * rand()) % 256).':'.
            self::macbyte(($seed * rand()) % 256).':'.
            self::macbyte(($seed * rand()) % 256);
    }

    /**
     * Generate new unused Mac address
     *
     * @param int $val Value
     *
     * @return int
     */
    public static function macbyte(int $val)
    {
        if ($val < 16) {
            return '0'.dechex($val);
        }
        return dechex($val);
    }

    /**
     * Generate new unused Mac address
     *
     * @param string $hypervisor_name Hypervisor name
     *
     * @return string
     */
    public static function genMacAddress(string $hypervisor_name = "qemu")
    {
        $mac = self::generateRandomMacAddress($hypervisor_name);
        // check if it's unused
        $usedMac = \VirtMan\Model\Network\Network::where(
            ['mac' => $mac]
        )->first();
        
        if (isset($usedMac->id)) {
            $mac = self::genMacAddress($hypervisor_name);
        }
        return $mac;
    }

    /**
     * Generate the next ip Address 
     *
     * @param string $ip 
     * 
     * @return string
     */
    public static function getNextIpAfter(string $ip)
    {
        $long = ip2long($ip);
        $result = long2ip(++$long);
        if (!self::validaUsableIpv4Address($result)) {
            return self::getNextIpAfter($result);
        }
        return $result;
    }

    /**
     * Generate the next ip Address 
     *
     * @param string $ip 
     * 
     * @return string
     */
    public static function validaUsableIpv4Address(string $ip)
    {
        $str = explode(".", $ip);
        if (count($str) === 4) {
            if ($str[3] > 0 && $str[3] < 255) {
                return true;
            }
        } 
        return false;
    }

    /**
     * Generate the next container's ip Address
     *
     * @return array
     */
    public static function genNextAvailableContainerIpAddress()
    {
        $ipAddress = "";
        
        // see if we have containers, and if we do use the last one's ip
        $lastMachine = \VirtMan\Model\Machine\Machine::orderBy('id', 'desc')
            ->first();

        if (isset($lastMachine->ip)) {
            $ipAddress = $lastMachine->ip;

        } else {

            // else use the config start 
            $ipAddress = Utils::getConfig("container_ip_start");
        }

        return self::getNextIpAfter($ipAddress);        
    }

    /**
     * Pre generate our container ip and mac address tables, 
     * so we can set them up on our network definition
     *
     * @param int $num 
     * 
     * @return array
     */
    public static function generateNetworkAddressPoolData(int $num)
    {
        $results = array();
        $ipAddress = Utils::getConfig("container_ip_start");

        for ($i = 0; $i < $num; $i++) {

            $ipAddress = self::getNextIpAfter($ipAddress);
            $results[] = [
                "ip" => $ipAddress,
                "mac" => self::genMacAddress()
            ];
        }
        return $results;
    }

    /**
     * Get container ip and mac address tables from current node, 
     *
     * @return array
     */
    public static function getNetworkAddressPoolData()
    {

        $dhcpNode = \VirtMan\Lib\Utils::getVirtManInstanceWithDHCP();
        $XML = $dhcpNode->getNetworkXML();

        $data = simplexml_load_string($XML);
        $hosts = json_decode(
            json_encode($data->ip->dhcp), 1
        );

        $results = array();
        foreach ($hosts["host"] as $host) {
            $results[] = [
                "mac" => $host["@attributes"]["mac"],
                "name" => $host["@attributes"]["name"],
                "ip" => $host["@attributes"]["ip"]
            ];
        }
        return $results;
    }


    /**
     * Get free ip and mac address for our new container
     *
     * @return array
     */
    public static function getFreeIpAndMacResource()
    {
        return \VirtMan\Model\Network\DhcpItem::whereNull("parent")
            ->orderBy('id', "ASC")->first();
    }

}