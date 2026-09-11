<?php

namespace Hoyvoy\Tests\Fixtures;

use Illuminate\Database\Query\Expression;

class StringableExpression extends Expression
{
    private $legacyText;

    public function __construct($value, string $legacyText)
    {
        parent::__construct($value);
        $this->legacyText = $legacyText;
    }

    public function __toString()
    {
        return $this->legacyText;
    }
}
