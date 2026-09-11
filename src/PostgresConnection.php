<?php

namespace Hoyvoy\CrossDatabase;

use Hoyvoy\CrossDatabase\Query\Grammars\PostgresGrammar as PostgresQueryGrammar;
use Illuminate\Database\PostgresConnection as IlluminatePostgresConnection;

class PostgresConnection extends IlluminatePostgresConnection implements CanCrossDatabaseShazaamInterface
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        if (method_exists(IlluminatePostgresConnection::class, 'withTablePrefix')) {
            return $this->withTablePrefix(new PostgresQueryGrammar());
        }

        return new PostgresQueryGrammar($this);
    }
}
