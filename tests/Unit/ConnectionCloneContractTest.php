<?php

namespace Hoyvoy\Tests\Unit;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class ConnectionCloneContractTest extends TestCase
{
    /** @dataProvider drivers */
    public function testFrozenVersionedConnectionLifecycle(array $case): void
    {
        $class = 'Hoyvoy\\CrossDatabase\\'.$case['driver'].'Connection';
        $denyPdo = function () {
            throw new \RuntimeException('Connection clone contract attempted database access');
        };
        $original = new $class($denyPdo, 'probe_a', $case['prefix']);
        $observed = ['initial' => $this->observe($original)];
        $original->setTablePrefix('late_');
        $observed['latePrefix'] = $this->observe($original);
        $copy = clone $original;
        $observed['cloneInitial'] = $this->observe($copy);
        $copy->setTablePrefix('clone_');
        $observed['cloneChanged'] = $this->observe($copy);
        $observed['originalAfterCloneChanged'] = $this->observe($original);
        $original->setTablePrefix('parent_');
        $observed['originalChangedAgain'] = $this->observe($original);
        $observed['cloneAfterOriginalChanged'] = $this->observe($copy);

        foreach ($case['expected'] as $state => $expected) {
            $this->assertSame($expected, $observed[$state], $state);
        }
    }

    public static function drivers(): array
    {
        $major = explode('.', Application::VERSION)[0];
        if (!in_array($major, ['9', '10', '11', '12'], true)) {
            throw new \RuntimeException('A new framework needs a measured connection lifecycle.');
        }
        // Published captures precede these tests. This difference is accepted debt,
        // not proof of harmlessness; see docs/connection-clone-debt.md for expiry.
        $fixture = json_decode(file_get_contents(__DIR__.'/../Fixtures/connection-clone-before.json'), true, 512, JSON_THROW_ON_ERROR);
        $cases = [];
        foreach ($fixture[$major] as $driver => $groups) {
            foreach ($groups as $prefix => $expected) {
                $cases[$driver.' prefix='.$prefix] = [[
                    'driver' => $driver, 'prefix' => $prefix === 'plain' ? '' : $prefix,
                    'expected' => $expected,
                ]];
            }
        }
        return $cases;
    }

    private function observe($connection): array
    {
        $query = $connection->table('orders')->where('orders.status', 'paid');
        $marker = $connection->table($connection->getTablePrefix().'<-->orders<-->probe_b')
            ->where('orders.status', 'paid');

        return [
            'connectionPrefix' => $connection->getTablePrefix(),
            'sql' => $query->toSql(),
            'markerSql' => $marker->toSql(),
            'bindings' => $query->getBindings(),
        ];
    }
}
