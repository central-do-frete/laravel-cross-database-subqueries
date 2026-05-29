<?php

namespace Hoyvoy\CrossDatabase\Eloquent\Concerns;

use Hoyvoy\CrossDatabase\CanCrossDatabaseShazaamInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait QueriesRelationships
{
    /**
     * Add the "has" condition where clause to the query.
     *
     * NOTE (Laravel 8 spike): the withCount() override was REMOVED. Laravel 5.7+ has
     * native cross-database subquery support (Builder::prependDatabaseNameIfCrossDatabaseQuery)
     * which already qualifies the withCount subselect; keeping the old override
     * double-qualified the schema (spike_b.spike_b.books). The has()/whereHas()
     * (whereExists) path is NOT covered by native support, so addHasWhere is still needed.
     */
    protected function addHasWhere(Builder $hasQuery, Relation $relation, $operator, $count, $boolean)
    {
        // If connection implements CanCrossDatabaseShazaamInterface we must attach database
        // connection name in from to be used by grammar when query compiled
        if ($this->getConnection() instanceof CanCrossDatabaseShazaamInterface) {
            $subqueryConnection = $hasQuery->getConnection()->getDatabaseName();
            $queryFrom = $hasQuery->getConnection()->getTablePrefix().'<-->'.$hasQuery->getQuery()->from.'<-->'.$subqueryConnection;
            $hasQuery->from($queryFrom);
        }

        return parent::addHasWhere($hasQuery, $relation, $operator, $count, $boolean);
    }
}
