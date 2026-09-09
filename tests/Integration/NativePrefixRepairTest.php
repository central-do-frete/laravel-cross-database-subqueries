<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\Tests\TestCase;
use Illuminate\Foundation\Application;

class NativePrefixRepairTest extends TestCase
{
    protected $tablesPrefix = 'p_';

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('database.connections.mysql1.database', 'probe_a');
        $app['config']->set('database.connections.mysql1.prefix', 'p_');
        $app['config']->set('database.connections.mysql2.database', 'probe_b');
    }

    /** @dataProvider prefixCases */
    public function testPrefixQualificationHasAnExplicitVersionedExpectation(string $name, array $case): void
    {
        $queries = [
            'withCount' => function () {
                return UserMysql::withCount('orders')->orderBy('id');
            },
            'whereIn-subquery' => function () {
                return UserMysql::whereIn('id', OrderMysql::select('user_id')->where('status', 'paid'))->orderBy('id');
            },
            'joinSub' => function () {
                return UserMysql::joinSub(
                    OrderMysql::select('user_id')->where('status', 'paid'),
                    'paid_orders',
                    'users.id',
                    '=',
                    'paid_orders.user_id'
                )->select('users.*')->orderBy('users.id');
            },
            'selectSub' => function () {
                return UserMysql::select('users.*')->selectSub(
                    OrderMysql::selectRaw('count(*)')->whereColumn('users.id', 'orders.user_id'),
                    'orders_count'
                )->orderBy('id');
            },
        ];
        $major = explode('.', Application::VERSION)[0];
        $this->assertContains($major, ['9', '10', '11'], 'A new framework needs a fresh probe and explicit expectation.');
        // Laravel 11 repairs the old unknown-database failure. The MySQL replay
        // verifies the rows; this offline suite freezes SQL and binding identity.
        $expected = $case[$major === '11' ? 'repaired' : 'legacy'];
        $query = $queries[$name]();
        $this->assertSame($expected['sql'], $query->toSql());
        $this->assertSame($expected['bindings'], $query->getBindings());
    }

    public function prefixCases(): array
    {
        $fixture = json_decode(file_get_contents(__DIR__.'/../Fixtures/mysql-prefix-expectations.json'), true, 512, JSON_THROW_ON_ERROR);
        $cases = [];
        foreach ($fixture['cases'] as $name => $case) {
            $cases[$name] = [$name, $case];
        }
        return $cases;
    }
}
