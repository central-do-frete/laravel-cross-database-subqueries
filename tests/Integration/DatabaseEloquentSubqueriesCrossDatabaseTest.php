<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model as Model;
use Hoyvoy\Tests\TestCase;

class DatabaseEloquentSubqueriesCrossDatabaseTest extends TestCase
{
    public function testWhereHasAcrossDatabaseConnection()
    {
        // Test MySQL cross database subquery
        $query = UserMysql::whereHas('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from `users` where exists (select * from `mysql2`.`prefix_orders` as `orders` where `users`.`id` = `orders`.`user_id` and `name` like ?)', $query->toSql());

        // Test MySQL same database subquery
        $query = UserMysql::whereHas('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from `users` where exists (select * from `mysql1`.`posts` where `users`.`id` = `posts`.`user_id` and `name` like ?)', $query->toSql());

        // Test PostgreSQL cross database subquery
        $query = UserPgsql::whereHas('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where exists (select * from "pgsql2"."prefix_orders" as "orders" where "users"."id" = "orders"."user_id" and "name"::text like ?)', $query->toSql());

        // Test PostgreSQL same database subquery
        $query = UserPgsql::whereHas('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where exists (select * from "pgsql1"."posts" where "users"."id" = "posts"."user_id" and "name"::text like ?)', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlsrv::whereHas('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from [users] where exists (select * from [sqlsrv2].[prefix_orders] as [orders] where [users].[id] = [orders].[user_id] and [name] like ?)', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlsrv::whereHas('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from [users] where exists (select * from [sqlsrv1].[posts] where [users].[id] = [posts].[user_id] and [name] like ?)', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlite::whereHas('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where exists (select * from "orders" where "users"."id" = "orders"."user_id" and "name" like ?)', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlite::whereHas('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where exists (select * from "posts" where "users"."id" = "posts"."user_id" and "name" like ?)', $query->toSql());
    }

    public function testHasAcrossDatabaseConnection()
    {
        // Test MySQL cross database subquery
        $query = UserMysql::has('orders');
        $this->assertEquals('select * from `users` where exists (select * from `mysql2`.`prefix_orders` as `orders` where `users`.`id` = `orders`.`user_id`)', $query->toSql());

        // Test MySQL same database subquery
        $query = UserMysql::has('posts');
        $this->assertEquals('select * from `users` where exists (select * from `mysql1`.`posts` where `users`.`id` = `posts`.`user_id`)', $query->toSql());

        // Test PostgreSQL cross database subquery
        $query = UserPgsql::has('orders');
        $this->assertEquals('select * from "users" where exists (select * from "pgsql2"."prefix_orders" as "orders" where "users"."id" = "orders"."user_id")', $query->toSql());

        // Test PostgreSQL same database subquery
        $query = UserPgsql::has('posts');
        $this->assertEquals('select * from "users" where exists (select * from "pgsql1"."posts" where "users"."id" = "posts"."user_id")', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlsrv::has('orders');
        $this->assertEquals('select * from [users] where exists (select * from [sqlsrv2].[prefix_orders] as [orders] where [users].[id] = [orders].[user_id])', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlsrv::has('posts');
        $this->assertEquals('select * from [users] where exists (select * from [sqlsrv1].[posts] where [users].[id] = [posts].[user_id])', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlite::has('orders');
        $this->assertEquals('select * from "users" where exists (select * from "orders" where "users"."id" = "orders"."user_id")', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlite::has('posts');
        $this->assertEquals('select * from "users" where exists (select * from "posts" where "users"."id" = "posts"."user_id")', $query->toSql());
    }

    public function testDoesntHasAcrossDatabaseConnection()
    {
        // Test MySQL cross database subquery
        $query = UserMysql::doesntHave('orders');
        $this->assertEquals('select * from `users` where not exists (select * from `mysql2`.`prefix_orders` as `orders` where `users`.`id` = `orders`.`user_id`)', $query->toSql());

        // Test MySQL same database subquery
        $query = UserMysql::doesntHave('posts');
        $this->assertEquals('select * from `users` where not exists (select * from `mysql1`.`posts` where `users`.`id` = `posts`.`user_id`)', $query->toSql());

        // Test PostgreSQL cross database subquery
        $query = UserPgsql::doesntHave('orders');
        $this->assertEquals('select * from "users" where not exists (select * from "pgsql2"."prefix_orders" as "orders" where "users"."id" = "orders"."user_id")', $query->toSql());

        // Test PostgreSQL same database subquery
        $query = UserPgsql::doesntHave('posts');
        $this->assertEquals('select * from "users" where not exists (select * from "pgsql1"."posts" where "users"."id" = "posts"."user_id")', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlsrv::doesntHave('orders');
        $this->assertEquals('select * from [users] where not exists (select * from [sqlsrv2].[prefix_orders] as [orders] where [users].[id] = [orders].[user_id])', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlsrv::doesntHave('posts');
        $this->assertEquals('select * from [users] where not exists (select * from [sqlsrv1].[posts] where [users].[id] = [posts].[user_id])', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlite::doesntHave('orders');
        $this->assertEquals('select * from "users" where not exists (select * from "orders" where "users"."id" = "orders"."user_id")', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlite::doesntHave('posts');
        $this->assertEquals('select * from "users" where not exists (select * from "posts" where "users"."id" = "posts"."user_id")', $query->toSql());
    }

    public function testWhereDoesntHaveAcrossDatabaseConnection()
    {
        // Test MySQL cross database subquery
        $query = UserMysql::whereDoesntHave('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from `users` where not exists (select * from `mysql2`.`prefix_orders` as `orders` where `users`.`id` = `orders`.`user_id` and `name` like ?)', $query->toSql());

        // Test MySQL same database subquery
        $query = UserMysql::whereDoesntHave('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from `users` where not exists (select * from `mysql1`.`posts` where `users`.`id` = `posts`.`user_id` and `name` like ?)', $query->toSql());

        // Test PostgreSQL cross database subquery
        $query = UserPgsql::whereDoesntHave('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where not exists (select * from "pgsql2"."prefix_orders" as "orders" where "users"."id" = "orders"."user_id" and "name"::text like ?)', $query->toSql());

        // Test PostgreSQL same database subquery
        $query = UserPgsql::whereDoesntHave('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where not exists (select * from "pgsql1"."posts" where "users"."id" = "posts"."user_id" and "name"::text like ?)', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlsrv::whereDoesntHave('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from [users] where not exists (select * from [sqlsrv2].[prefix_orders] as [orders] where [users].[id] = [orders].[user_id] and [name] like ?)', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlsrv::whereDoesntHave('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from [users] where not exists (select * from [sqlsrv1].[posts] where [users].[id] = [posts].[user_id] and [name] like ?)', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlite::whereDoesntHave('orders', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where not exists (select * from "orders" where "users"."id" = "orders"."user_id" and "name" like ?)', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlite::whereDoesntHave('posts', function ($query) {
            $query->where('name', 'like', '%a%');
        });
        $this->assertEquals('select * from "users" where not exists (select * from "posts" where "users"."id" = "posts"."user_id" and "name" like ?)', $query->toSql());
    }

    /**
     * @todo support prefixes
     */
    public function testWithCountAcrossDatabaseConnection()
    {
        // Test MySQL cross database subquery ()
        $query = UserMysql::withCount(['ordersWithoutPrefix' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select `users`.*, (select count(*) from `mysql3`.`orders` where `users`.`id` = `orders`.`user_id` and `name` like ?) as `orders_without_prefix_count` from `users`', $query->toSql());

        // Test MySQL same database subquery
        $query = UserMysql::withCount(['posts' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select `users`.*, (select count(*) from `posts` where `users`.`id` = `posts`.`user_id` and `name` like ?) as `posts_count` from `users`', $query->toSql());

        // Test PostgreSQL cross database subquery
        $query = UserPgsql::withCount(['ordersWithoutPrefix' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select "users".*, (select count(*) from "pgsql3"."orders" where "users"."id" = "orders"."user_id" and "name"::text like ?) as "orders_without_prefix_count" from "users"', $query->toSql());

        // Test PostgreSQL same database subquery
        $query = UserPgsql::withCount(['posts' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select "users".*, (select count(*) from "posts" where "users"."id" = "posts"."user_id" and "name"::text like ?) as "posts_count" from "users"', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlsrv::withCount(['ordersWithoutPrefix' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select [users].*, (select count(*) from [sqlsrv3].[orders] where [users].[id] = [orders].[user_id] and [name] like ?) as [orders_without_prefix_count] from [users]', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlsrv::withCount(['posts' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select [users].*, (select count(*) from [posts] where [users].[id] = [posts].[user_id] and [name] like ?) as [posts_count] from [users]', $query->toSql());

        // Test SQL Server cross database subquery
        $query = UserSqlite::withCount(['ordersWithoutPrefix' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select "users".*, (select count(*) from "sqlite3"."orders" where "users"."id" = "orders"."user_id" and "name" like ?) as "orders_without_prefix_count" from "users"', $query->toSql());

        // Test SQL Server same database subquery
        $query = UserSqlite::withCount(['posts' => function ($query) {
            $query->where('name', 'like', '%a%');
        },
        ]);
        $this->assertEquals('select "users".*, (select count(*) from "posts" where "users"."id" = "posts"."user_id" and "name" like ?) as "posts_count" from "users"', $query->toSql());
    }
}

