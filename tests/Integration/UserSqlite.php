<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class UserSqlite extends Model
{
    protected $connection = 'sqlite1';
    protected $table = 'users';
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderSqlite', 'user_id');
    }

    public function ordersWithoutPrefix()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderWithoutPrefixSqlite', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\PostSqlite', 'user_id');
    }
}

