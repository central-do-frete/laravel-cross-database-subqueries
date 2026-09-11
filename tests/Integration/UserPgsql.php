<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class UserPgsql extends Model
{
    protected $connection = 'pgsql1';
    protected $table = 'users';
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderPgsql', 'user_id');
    }

    public function ordersWithoutPrefix()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\OrderWithoutPrefixPgsql', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('Hoyvoy\Tests\Integration\PostPgsql', 'user_id');
    }
}

