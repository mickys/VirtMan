<?php
/**
 * This file is part of the PHP VirtMan package
 *
 * PHP Version 7.2
 *
 * @category VirtMan\Tests
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
namespace VirtMan\Tests;

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Cache\NullStore;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Routing\Router;
use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;
use Jumilla\Versionia\Laravel\Migrator;

use Mockery as m;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * TestCase abstract class
 *
 * @category VirtMan\Tests
 * @package  VirtMan
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/VirtMan/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/VirtMan/
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Setup SandBox
     * 
     * @return void
     */
    public function setupSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox/addons');
        $files->makeDirectory(__DIR__.'/sandbox/app');
        $files->makeDirectory(__DIR__.'/sandbox/config');
    }

    /**
     * Teardown SandBox
     * 
     * @return void
     */
    public function teardownSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
    }
    
    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function createApplication()
    {
        Container::setInstance($this->app = new ApplicationStub([]));
        $this->app[Illuminate\Contracts\Foundation\Application::class] = $this->app;
        $this->app[Illuminate\Contracts\Cache\Repository::class] = new Cache(
            new NullStore()
        );
        $this->app['config'] = new Config(
            Array( 'database' => Array(
                'default' => 'sqlite',
                'connections' => Array(
                    'sqlite' => Array(
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                        'prefix' => '',
                        'foreign_key_constraints' => 1,
                        )
                    ),
                ),
            )
        );

        $this->app['events'] = new Dispatcher($this->app);
        $this->app['files'] = new Filesystem();
        $this->app['filesystem'] = new FilesystemManager($this->app);
        $this->app['router'] = new Router($this->app['events'], $this->app);

        $this->app['db'] = new DatabaseManager(
            $this->app,
            new ConnectionFactory($this->app)
        );

        return $this->app;
    }

    /**
     * Create Migrator
     * 
     * @param array $overrides Overrides
     *
     * @return void
     */
    /*
    public function createMigrator(array $overrides = null)
    {
        $this->app['db'] = $this->createMock(DatabaseManager::class);
        $migrator = $this->createCustomMock( 
            Migrator::class, 
            $overrides, 
            function () {
                return [$this->app['db']];
            }
        );
        $this->app->instance('database.migrator', $migrator);
        $this->app->alias('database.migrator', Migrator::class);
        return $migrator;
    }
    */

    /**
     * Test teardown
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Create Mock
     * 
     * @param string    $class     Class name
     * @param array     $overrides Overrides
     * @param overrides $callback  Callback
     *
     * @return \Mockery\MockInterface
     */
    protected function createCustomMock(
        string $class, 
        array $overrides = null,
        callable $callback = null
    ) {
        // override all methods
        if ($overrides === null) {
            return m::mock($class);

        } else { // override partial methods
            return m::mock(
                $class.'['.implode(', ', $overrides).']',
                $callback ? call_user_func($callback) : []
            );
        }
    }
}
