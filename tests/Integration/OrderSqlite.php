<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class OrderSqlite extends Model
{
    protected $connection = 'sqlite2';
    protected $table = 'orders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('Hoyvoy\Tests\Integration\UserSqlite', 'user_id');
    }
}
