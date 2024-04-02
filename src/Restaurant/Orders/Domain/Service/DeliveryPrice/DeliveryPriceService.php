<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Service\DeliveryPrice;

use App\Restaurant\Deliveries\Application\CalculateRate\CalculateDeliveryRateQuery;
use App\Restaurant\Deliveries\Application\DeliveryResponse;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\DomainService;
use App\Shared\Domain\ValueObject\Price;

class DeliveryPriceService implements DomainService
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): Price
    {
        /** @var DeliveryResponse $response */
        $response = $this->queryBus->run(new CalculateDeliveryRateQuery());

        return Price::fromInteger($response->amount);
    }
}
