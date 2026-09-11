<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class UserMysql extends Model
{
    protected $connection = 'mysql1';
    protected $table = 'users';
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderMysql', 'user_id');
    }

    public function ordersWithoutPrefix()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderWithoutPrefixMysql', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\PostMysql', 'user_id');
    }
}

