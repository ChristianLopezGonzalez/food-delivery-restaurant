<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObjets;

use App\Shared\Domain\ValueObject\Price;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class PriceMother
{
    public static function create(?int $value = null): Price
    {
        $value = $value ?? MotherCreator::random()->randomNumber(5);
        return Price::fromInteger($value);
    }

    public static function createZero(): Price
    {
        return Price::zero();
    }
}
