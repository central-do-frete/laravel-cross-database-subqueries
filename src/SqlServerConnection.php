<?php

namespace Hoyvoy\CrossDatabase;

use Hoyvoy\CrossDatabase\Query\Grammars\SqlServerGrammar as SqlServerQueryGrammar;
use Illuminate\Database\SqlServerConnection as IlluminateSqlServerConnection;

class SqlServerConnection extends IlluminateSqlServerConnection implements CanCrossDatabaseShazaamInterface
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\SqlServerGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        if (method_exists(IlluminateSqlServerConnection::class, 'withTablePrefix')) {
            return $this->withTablePrefix(new SqlServerQueryGrammar());
        }

        return new SqlServerQueryGrammar($this);
    }
}
