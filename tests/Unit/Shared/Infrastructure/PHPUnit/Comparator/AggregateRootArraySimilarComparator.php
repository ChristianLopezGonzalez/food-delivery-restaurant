<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\PHPUnit\Comparator;

use App\Shared\Domain\AggregateRoot;
use App\Tests\Unit\Shared\Domain\TestUtils;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

final class AggregateRootArraySimilarComparator extends Comparator
{
    public function accepts($expected, $actual): bool
    {
        return is_array($expected)
            && is_array($actual)
            && count(array_filter($expected, static fn($item) => $item instanceof AggregateRoot)) === count($expected)
            && count(array_filter($actual, static fn($item) => $item instanceof AggregateRoot)) === count($actual);
    }

    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        if (count($expected) !== count($actual) || !$this->contains($expected, $actual)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter->export($expected),
                $this->exporter->export($actual),
                'Failed asserting the collection of AGs contains all the expected elements.',
            );
        }
    }

    private function contains(array $expectedArray, array $actualArray): bool
    {
        /** @var AggregateRoot $expected */
        foreach ($expectedArray as $expected) {
            if (!count(array_filter($actualArray, fn($actual) => TestUtils::isSimilar($expected, $actual)))) {
                return false;
            }
        }
        return true;
    }
}
