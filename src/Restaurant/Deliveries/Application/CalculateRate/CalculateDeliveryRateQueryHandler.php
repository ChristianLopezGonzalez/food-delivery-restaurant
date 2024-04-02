<?php

declare(strict_types=1);

namespace App\Restaurant\Deliveries\Application\CalculateRate;

use App\Restaurant\Deliveries\Application\DeliveryResponse;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class CalculateDeliveryRateQueryHandler implements QueryHandler
{
    public function __construct(private readonly CalculateDeliveryRate $calculator)
    {
    }

    public function __invoke(CalculateDeliveryRateQuery $query): DeliveryResponse
    {
        $deliveryRate = $this->calculator->__invoke();

        return DeliveryResponse::fromDelivery($deliveryRate);
    }
}
