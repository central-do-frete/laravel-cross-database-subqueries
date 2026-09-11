<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class OrderWithoutPrefixSqlite extends Model
{
    protected $connection = 'sqlite3';
    protected $table = 'orders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('Hoyvoy\Tests\Integration\UserMysql', 'user_id');
    }
}

