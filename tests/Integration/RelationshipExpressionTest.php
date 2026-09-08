<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\Tests\Fixtures\StringableExpression;
use Hoyvoy\Tests\TestCase;
use Illuminate\Database\Query\Expression;

class RelationshipExpressionTest extends TestCase
{
    /** @dataProvider relationshipInputs */
    public function testMarkerRetainsTheRelationshipSql(string $relation, string $kind, int $count): void
    {
        $query = UserMysql::whereHas($relation, function ($query) use ($relation, $kind) {
            $table = $relation === 'posts' ? 'posts' : 'orders';
            $from = $kind === 'stringable' ? new StringableExpression('wrong_table', $table)
                : ($kind === 'expression' ? new Expression($table) : $table);
            $query->from($from)->where('name', 'like', '%a%');
        }, '>=', $count);
        $tableSql = $relation === 'posts' ? '`mysql1`.`posts`' : '`mysql2`.`prefix_orders` as `orders`';
        $select = $count === 1 ? '*' : 'count(*)';
        $predicate = $count === 1 ? 'exists (' : '(';
        $suffix = $count === 1 ? ')' : ') >= 2';
        $expected = 'select * from `users` where '.$predicate.'select '.$select.' from '.$tableSql
            .' where `users`.`id` = `'.$relation.'`.`user_id` and `name` like ?'.$suffix;
        if ($relation === 'orders' && $count === 2) {
            // Preserve the existing prefixed-count SQL, including its unresolved column prefix.
            $expected = 'select * from `users` where (select count(*) from `mysql2`.`prefix_orders`'
                .' where `prefix_users`.`id` = `prefix_orders`.`user_id` and `name` like ?) >= 2';
        }
        $this->assertSame($expected, $query->toSql());
        $this->assertSame(['%a%'], $query->getBindings());
    }

    public function relationshipInputs(): array
    {
        $cases = [];
        foreach (['posts', 'orders'] as $relation) {
            foreach (['string', 'expression', 'stringable'] as $kind) {
                foreach ([1, 2] as $count) {
                    $cases[$relation.' '.$kind.' count='.$count] = [$relation, $kind, $count];
                }
            }
        }
        return $cases;
    }
}
