<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Config
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
use VirtMan\VirtManServiceProvider;

return [
  /*
   * VirtMan Configuration File
   *
   * username: The libvirt username used for authentication
   * password: Password used to authenticate with Libvirt
   * storageQuota: Maximum size available for Storage Images
   * memoryQuota: Maximum memory available for Machines
   */
   'username' => 'root',
   'password' => 'password',
   'storageQuota' => '0',
   'memoryQuota' => '0',
   'storageLocation' => '/var/lib/libvirt/images',
   'connectionType' => 'test',
   'usingSSH' => false,
   'sshUser' => '',
   'sshPassword' => '',
   'remoteUrl' => 'test:///default"', // Test "mock" driver
   'daemonMode' => 'default'
];
