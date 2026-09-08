<?php

namespace Hoyvoy\Tests\Integration;

use Hoyvoy\CrossDatabase\Eloquent\Model;

class PostSqlsrv extends Model
{
    protected $connection = 'sqlsrv1';
    protected $table = 'posts';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('Hoyvoy\Tests\Integration\UserSqlsrv', 'user_id');
    }
}

