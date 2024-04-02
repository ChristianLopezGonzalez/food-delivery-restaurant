<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Errors;

use App\Shared\Domain\DomainError;

final class DeliveryOrderRequiresEqualTotalPrice extends DomainError
{
    public function __construct(private readonly float $totalPriceExpected)
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf('Money must be the exact order amount on delivery orders (%s)', $this->totalPriceExpected);
    }
}
