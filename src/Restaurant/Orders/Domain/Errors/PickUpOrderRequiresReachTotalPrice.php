<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Errors;

use App\Shared\Domain\DomainError;

final class PickUpOrderRequiresReachTotalPrice extends DomainError
{
    public function __construct(private readonly string $totalPriceExpected)
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf('Money does not reach the order amount (%s)', $this->totalPriceExpected);
    }
}
