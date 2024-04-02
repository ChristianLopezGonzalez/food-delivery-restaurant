<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Application\FindByCode;

use App\Restaurant\Products\Application\ProductResponse;
use App\Restaurant\Products\Domain\Errors\ProductNotFound;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class FindByProductCodeQueryHandler implements QueryHandler
{
    public function __construct(private readonly FindByProductCode $finder)
    {
    }

    /**
     * @throws ProductNotFound
     */
    public function __invoke(FindByProductCodeQuery $query): ProductResponse
    {
        $product = $this->finder->__invoke($query->code);

        return ProductResponse::fromProduct($product);
    }
}
