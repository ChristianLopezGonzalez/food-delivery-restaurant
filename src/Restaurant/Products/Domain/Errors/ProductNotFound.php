<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Domain\Errors;

use App\Shared\Domain\DomainError;

final class ProductNotFound extends DomainError
{
    public function __construct(private readonly string $productCode)
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf('Product with code "%s" not found', $this->productCode);
    }
}
