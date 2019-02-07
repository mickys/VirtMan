<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 * 
 * @category VirtMan
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */

namespace VirtMan;

use Illuminate\Support\ServiceProvider;

/**
 * VirtMan Service Provider
 *
 * @category VirtMan
 * @package  VirtMan
 * @author   Ryan Owens <RyanOwens@linux.com>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
class VirtManServiceProvider extends ServiceProvider
{
    /**
     * Boot Service Provider
     * 
     * @return void
     */
    public function boot()
    {
        // migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        // config files
        $this->publishes(
            [__DIR__ . '/Config/VirtManConfig.php' => config_path('virtman.php')]
        );
    }

    /**
     * Register Service Provider
     * 
     * @return void
     */
    public function register()
    {
        // config
    }
}
