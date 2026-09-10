<?php

namespace Hoyvoy\Tests\Unit;

use Hoyvoy\CrossDatabase\MySqlConnection;
use Hoyvoy\Tests\Fixtures\StringableExpression;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use PHPUnit\Framework\TestCase;

class MySqlExpressionTest extends TestCase
{
    /** @dataProvider expressions */
    public function testCompiledSqlRetainsTheLaravel9Contract(array $case): void
    {
        $connection = new MySqlConnection(function () {
            throw new \RuntimeException('Compile-only test attempted database access');
        }, 'probe_a', $case['prefix']);
        $grammar = $connection->getQueryGrammar();
        $expression = $case['name'] === 'customString'
            ? new StringableExpression($case['input'], '<-->users<-->legacy_database')
            : new Expression($case['input']);
        $query = (new Builder($connection, $grammar))->from($expression)->where('status', 'active');
        $this->assertSame($case['sql'], $query->toSql());
        $this->assertSame($case['bindings'], $query->getBindings());
    }

    public function expressions(): array
    {
        $cases = json_decode(file_get_contents(__DIR__.'/../Fixtures/mysql-expression-laravel9.json'), true, 512, JSON_THROW_ON_ERROR);
        $data = [];
        foreach ($cases as $case) {
            $data[$case['name'].' prefix='.$case['prefix']] = [$case];
        }
        return $data;
    }
}
