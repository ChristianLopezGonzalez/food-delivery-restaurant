<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use App\Tests\Unit\Shared\Infrastructure\Mockery\MatcherIsSimilar;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\Constraint\ConstraintIsSimilar;

final class TestUtils
{
    public static function isSimilar(mixed $expected, mixed $actual): bool
    {
        return (new ConstraintIsSimilar($expected))->evaluate($actual, '', false);
    }

    public static function similarTo(mixed $value, float $delta = 0.0): MatcherIsSimilar
    {
        return new MatcherIsSimilar($value, $delta);
    }
}
