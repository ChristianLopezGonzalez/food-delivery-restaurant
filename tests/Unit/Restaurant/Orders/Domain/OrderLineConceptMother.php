<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderLineConcept;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class OrderLineConceptMother
{
    public static function create(?string $value = null): OrderLineConcept
    {
        $value = $value ?? MotherCreator::random()->randomElement(['pizza', 'burger', 'sushi']);
        return new OrderLineConcept($value);
    }
}
