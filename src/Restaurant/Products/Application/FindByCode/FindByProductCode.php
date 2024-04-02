<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Application\FindByCode;

use App\Restaurant\Products\Domain\Errors\ProductNotFound;
use App\Restaurant\Products\Domain\Product;
use App\Restaurant\Products\Domain\ProductCode;
use App\Restaurant\Products\Domain\ProductRepository;
use App\Shared\Domain\Bus\Query\Query;

final class FindByProductCode implements Query
{
    public function __construct(private readonly ProductRepository $repository)
    {
    }

    /**
     * @throws ProductNotFound
     */
    public function __invoke(string $code): Product
    {
        $product = $this->repository->searchByCode(new ProductCode($code));

        if ($product === null) {
            throw new ProductNotFound($code);
        }

        return $product;
    }
}
