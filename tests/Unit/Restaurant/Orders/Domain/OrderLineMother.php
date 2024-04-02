<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderLine;
use App\Tests\Unit\Shared\Domain\ValueObjets\PriceMother;

final class OrderLineMother
{
    public static function create(?string $concept = null, ?int $price = null, ?int $amount = null,): OrderLine
    {
        $concept = OrderLineConceptMother::create($concept);
        $price = PriceMother::create($price);
        $amount = OrderLineAmountMother::create($amount);
        return new OrderLine($concept, $price, $amount);
    }
}
