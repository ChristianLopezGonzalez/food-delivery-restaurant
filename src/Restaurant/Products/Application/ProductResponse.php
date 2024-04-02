<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Application;

use App\Restaurant\Products\Domain\Product;
use App\Shared\Domain\Bus\Response;

final class ProductResponse implements Response
{
    public function __construct(
        public readonly string $code,
        public readonly int $amount,
        public readonly string $currency
    ) {
    }

    public static function fromProduct(Product $product): self
    {
        return new self(
            $product->code()->value(),
            $product->price()->value(),
            $product->price()->currency()->value(),
        );
    }
}
