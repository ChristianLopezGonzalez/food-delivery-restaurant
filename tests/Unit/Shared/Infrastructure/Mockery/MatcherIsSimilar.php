<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Mockery;

use App\Tests\Unit\Shared\Infrastructure\PHPUnit\Constraint\ConstraintIsSimilar;
use Mockery\Matcher\MatcherAbstract;

final class MatcherIsSimilar extends MatcherAbstract
{
    private ConstraintIsSimilar $constraint;

    /**
     * @param mixed $value
     * @param float $delta
     */
    public function __construct($value, $delta = 0.0)
    {
        parent::__construct($value);

        $this->constraint = new ConstraintIsSimilar($value, $delta);
    }

    public function match(&$actual): bool
    {
        return $this->constraint->evaluate($actual, '', true);
    }

    public function __toString(): string
    {
        return 'Is similar';
    }
}
