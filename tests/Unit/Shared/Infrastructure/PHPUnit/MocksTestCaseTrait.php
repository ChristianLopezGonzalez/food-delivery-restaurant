<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\PHPUnit;

use App\Tests\Unit\Shared\Domain\TestUtils;
use Mockery\Matcher\MatcherAbstract;

trait MocksTestCaseTrait
{
    protected function similarTo($value, $delta = 0.0): MatcherAbstract
    {
        return TestUtils::similarTo($value, $delta);
    }
}
