<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Infrastructure\Storage;

use App\Restaurant\Products\Domain\Product;
use App\Restaurant\Products\Domain\ProductCode;
use App\Restaurant\Products\Domain\ProductRepository;
use App\Shared\Domain\ValueObject\Price;

final class InMemoryProductRepository implements ProductRepository
{
    private const PRODUCTS = [
        'pizza' => [
            'price' => 1250,
        ],
        'burger' => [
            'price' => 900,
        ],
        'sushi' => [
            'price' => 2400,
        ],
        'nuggets' => [
            'price' => 600,
        ],
        'kebap' => [
            'price' => 200,
        ],
        'drink' => [
            'price' => 200,
        ],
    ];

    public function searchByCode(ProductCode $code): ?Product
    {
        if (!array_key_exists($code->value(), self::PRODUCTS)) {
            return null;
        }

        $price = Price::fromInteger(self::PRODUCTS[$code->value()]['price']);
        return new Product($code, $price);
    }
}
