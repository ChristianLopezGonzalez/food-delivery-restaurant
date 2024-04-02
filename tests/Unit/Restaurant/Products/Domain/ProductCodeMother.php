<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Products\Domain;

use App\Restaurant\Products\Domain\ProductCode;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class ProductCodeMother
{
    public static function create(?string $value = null): ProductCode
    {
        $value = $value ?? MotherCreator::random()->lexify('?????');
        return new ProductCode($value);
    }
}
