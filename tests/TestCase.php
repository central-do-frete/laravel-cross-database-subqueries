<?php

namespace Hoyvoy\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Container\Container;
use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Hoyvoy\CrossDatabase\CrossDatabaseServiceProvider;

class TestCase extends BaseTestCase
{
    protected $tablesPrefix = 'prefix_';

    protected function setUp(): void
    {
        parent::setUp();
        $app = new Container();
        $app->instance('config', new Repository());
        $this->getEnvironmentSetUp($app);
        $provider = new CrossDatabaseServiceProvider($app);
        $provider->register();
        $app->instance('db', new DatabaseManager($app, $app['db.factory']));
        $app->instance('events', new Dispatcher($app));
        $provider->boot();
        foreach (array_keys($app['config']->get('database.connections')) as $name) {
            $deny = function () use ($name) {
                throw new \RuntimeException('Compile-only test attempted database access: '.$name);
            };
            $app['db']->connection($name)->setPdo($deny)->setReadPdo($deny);
        }
    }

    /**
     * Define environment setup.
     *
     * @param Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.connections', [
            'mysql1' => [
                'driver'      => 'mysql',
                'host'        => '127.0.0.1',
                'port'        => '3306',
                'database'    => 'mysql1',
                'username'    => 'test',
                'password'    => 'test',
                'unix_socket' => '',
                'charset'     => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
            ],
            'mysql2' => [
                'driver'      => 'mysql',
                'host'        => '127.0.0.1',
                'port'        => '3306',
                'database'    => 'mysql2',
                'username'    => 'test',
                'password'    => 'test',
                'unix_socket' => '',
                'charset'     => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'prefix'      => $this->tablesPrefix,
                'strict'      => true,
                'engine'      => null,
            ],
            'mysql3' => [
                'driver'      => 'mysql',
                'host'        => '127.0.0.1',
                'port'        => '3306',
                'database'    => 'mysql3',
                'username'    => 'test',
                'password'    => 'test',
                'unix_socket' => '',
                'charset'     => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
            ],
            'pgsql1' => [
                'driver'   => 'pgsql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'pgsql1',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
                'sslmode'  => 'prefer',
            ],
            'pgsql2' => [
                'driver'   => 'pgsql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'pgsql2',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => $this->tablesPrefix,
                'schema'   => 'public',
                'sslmode'  => 'prefer',
            ],
            'pgsql3' => [
                'driver'   => 'pgsql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'pgsql3',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
                'sslmode'  => 'prefer',
            ],
            'sqlsrv1' => [
                'driver'   => 'sqlsrv',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'sqlsrv1',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => '',
            ],
            'sqlsrv2' => [
                'driver'   => 'sqlsrv',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'sqlsrv2',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => $this->tablesPrefix,
            ],
            'sqlsrv3' => [
                'driver'   => 'sqlsrv',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'database' => 'sqlsrv3',
                'username' => 'test',
                'password' => 'test',
                'charset'  => 'utf8',
                'prefix'   => '',
            ],
            'sqlite1' => [
                'driver'    => 'sqlite',
                'database'  => 'sqlite1',
                'prefix'    => '',
            ],
            'sqlite2' => [
                'driver'    => 'sqlite',
                'database'  => 'sqlite2',
                'prefix'    => $this->tablesPrefix,
            ],
            'sqlite3' => [
                'driver'    => 'sqlite',
                'database'  => 'sqlite3',
                'prefix'    => '',
            ],
        ]);
    }
}
