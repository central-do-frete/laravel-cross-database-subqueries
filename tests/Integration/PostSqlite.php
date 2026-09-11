<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class PostSqlite extends Model
{
    protected $connection = 'sqlite1';
    protected $table = 'posts';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('Hoyvoy\Tests\Integration\UserSqlite', 'user_id');
    }
}

