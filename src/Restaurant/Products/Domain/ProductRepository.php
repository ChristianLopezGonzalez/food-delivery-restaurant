<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Domain;

interface ProductRepository
{
    public function searchByCode(ProductCode $code): ?Product;
}
