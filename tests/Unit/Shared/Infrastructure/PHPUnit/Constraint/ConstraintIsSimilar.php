<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\PHPUnit\Constraint;

use App\Tests\Unit\Shared\Infrastructure\PHPUnit\Comparator\AggregateRootArraySimilarComparator;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\Comparator\AggregateRootSimilarComparator;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;

// Based on \PHPUnit\Framework\Constraint\IsEqual
final class ConstraintIsSimilar extends Constraint
{
    public function __construct(private mixed $value, private float $delta = 0.0)
    {
    }

    public function evaluate(mixed $other, mixed $description = '', $returnResult = true): bool
    {
        if ($this->value === $other) {
            return true;
        }

        $isValid = true;
        $comparatorFactory = new Factory();

        $comparatorFactory->register(new AggregateRootArraySimilarComparator());
        $comparatorFactory->register(new AggregateRootSimilarComparator());

        try {
            $comparator = $comparatorFactory->getComparatorFor($other, $this->value);

            $comparator->assertEquals($this->value, $other, $this->delta);
        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                throw new ExpectationFailedException(trim($description . "\n" . $f->getMessage()), $f);
            }

            $isValid = false;
        }

        return $isValid;
    }

    public function toString(): string
    {
        $delta = '';

        if (is_string($this->value)) {
            if (str_contains($this->value, "\n")) {
                return 'is equal to <text>';
            }

            return sprintf("is equal to '%s'", $this->value);
        }

        if ($this->delta !== 0.0) {
            $delta = sprintf(' with delta <%F>', $this->delta);
        }

        return sprintf('is equal to %s%s', $this->exporter()->export($this->value), $delta);
    }
}
