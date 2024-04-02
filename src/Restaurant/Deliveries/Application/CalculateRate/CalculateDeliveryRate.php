<?php

declare(strict_types=1);

namespace App\Restaurant\Deliveries\Application\CalculateRate;

use App\Restaurant\Deliveries\Domain\Delivery;
use App\Shared\Application\UseCase;

final class CalculateDeliveryRate implements UseCase
{
    public function __invoke(): Delivery
    {
        return Delivery::createFlatRate();
    }
}
