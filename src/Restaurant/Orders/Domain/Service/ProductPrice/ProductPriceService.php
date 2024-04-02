<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Service\ProductPrice;

use App\Restaurant\Products\Application\FindByCode\FindByProductCodeQuery;
use App\Restaurant\Products\Application\ProductResponse;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\DomainService;
use App\Shared\Domain\ValueObject\Price;

class ProductPriceService implements DomainService
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $productCode): Price
    {
        /** @var ProductResponse $response */
        $response = $this->queryBus->run(new FindByProductCodeQuery($productCode));

        return Price::fromInteger($response->amount);
    }
}
