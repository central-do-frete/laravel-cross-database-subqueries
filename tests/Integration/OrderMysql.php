<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class OrderMysql extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'orders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('Hoyvoy\Tests\Integration\UserMysql', 'user_id');
    }
}

