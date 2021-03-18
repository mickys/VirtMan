# VirtMan
A Libvirt PHP wrapper library for the [Laravel Framework](https://laravel.com/).
_version **0.0.2**_
## Requirements
* PHP >= 7.0
* Libvirt PHP extension

## Installation
First grab the package via composer.
```bash
composer require ryanvade/virtman
```
Then add the service provider to the list of providers in config/app.php.
```php
...
        /*
         * Package Service Providers...
         */

        VirtMan\VirtManServiceProvider::class,
...
```
Finally publish the package to your laravel project and run the migrations.
```bash
php artisan vendor:publish
php artisan migrate
```

## TODO
- [x] Create Virtual Machines using XML
- [x] Create Storage Images
- [x] Create Storage Pools
- [x] Create Networks
- [ ] Create Machine Groups
- [x] Delete Virtual Machines
- [x] Delete Storage Images
- [x] Delete Storage Pools
- [ ] Delete Networks
- [ ] Delete Machine Groups
- [x] List Networks
- [x] List Machines
- [ ] Change Machine Settings
- [ ] Change Machine Networks
- [ ] Add a Machine to a Network
- [ ] Clone a Machine
- [X] Add Storage to a Storage Pool
- [X] Get Machine Network interface list
- [X] Get Machine Network interface statistics

## Credits

Forked from [https://github.com/ryanvade/VirtMan](https://github.com/ryanvade/VirtMan)
