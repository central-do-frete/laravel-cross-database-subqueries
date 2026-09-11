<?php

namespace Hoyvoy\Tests\Unit;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class GrammarApiContractTest extends TestCase
{
    /** @dataProvider drivers */
    public function testRealConnectionFactoryPreservesOrdinarySql(array $case): void
    {
        $class = 'Hoyvoy\\CrossDatabase\\'.$case['driver'].'Connection';
        $connection = new $class($this->denyPdo(), 'probe_a', $case['prefix']);
        $this->assertSame('Hoyvoy\\CrossDatabase\\Query\\Grammars\\'.$case['driver'].'Grammar', get_class($connection->getQueryGrammar()));
        $query = $connection->table('orders')->where('orders.status', 'paid');
        $this->assertSame($case['ordinary'], $query->toSql());
        $this->assertSame($case['bindings'], $query->getBindings());
    }

    /** @dataProvider drivers */
    public function testMarkerCompilerIsCheckedIndependentlyOfTheConnectionFactory(array $case): void
    {
        // The untouched compiler must be observable even when its factory cannot construct.
        $native = 'Illuminate\\Database\\'.$case['driver'].'Connection';
        $connection = new $native($this->denyPdo(), 'probe_a', $case['prefix']);
        $class = 'Hoyvoy\\CrossDatabase\\Query\\Grammars\\'.$case['driver'].'Grammar';
        $constructor = (new \ReflectionClass($class))->getConstructor();
        $grammar = $constructor ? new $class($connection) : new $class();
        if (method_exists($grammar, 'setTablePrefix')) {
            $grammar->setTablePrefix($case['prefix']);
        }
        $query = (new Builder($connection, $grammar))
            ->from($case['prefix'].'<-->orders<-->probe_b')->where('orders.status', 'paid');
        $this->assertSame($case['marker'], $query->toSql());
        $this->assertSame($case['bindings'], $query->getBindings());
    }

    public static function drivers(): array
    {
        $major = explode('.', Application::VERSION)[0];
        if (!in_array($major, ['9', '10', '11', '12'], true)) {
            throw new \RuntimeException('A new framework needs a measured SQL oracle.');
        }
        $fixture = json_decode(file_get_contents(__DIR__.'/../Fixtures/grammar-api-before.json'), true, 512, JSON_THROW_ON_ERROR);
        // The approved Laravel 12 repair must reproduce the frozen Laravel 11 result.
        $cases = [];
        foreach ($fixture[$major === '12' ? '11' : $major] as $case) {
            $cases[$case['driver'].' prefix='.$case['prefix']] = [$case];
        }
        return $cases;
    }

    private function denyPdo(): \Closure
    {
        return function () {
            throw new \RuntimeException('Compile-only contract attempted database access');
        };
    }
}
