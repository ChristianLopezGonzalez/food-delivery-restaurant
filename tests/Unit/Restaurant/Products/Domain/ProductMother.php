<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Products\Domain;

use App\Restaurant\Products\Domain\Product;
use App\Tests\Unit\Shared\Domain\ValueObjets\PriceMother;

final class ProductMother
{
    public static function create(?string $code = null, ?int $price = null): Product
    {
        return new Product(
            code: ProductCodeMother::create($code),
            price: PriceMother::create($price),
        );
    }
}
