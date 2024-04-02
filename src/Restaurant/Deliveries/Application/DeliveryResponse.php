<?php

declare(strict_types=1);

namespace App\Restaurant\Deliveries\Application;

use App\Restaurant\Deliveries\Domain\Delivery;
use App\Shared\Domain\Bus\Response;

final class DeliveryResponse implements Response
{
    public function __construct(public int $amount, public string $currency)
    {
    }

    public static function fromDelivery(Delivery $delivery): self
    {
        return new self(
            $delivery->price()->value(),
            $delivery->price()->currency()->value(),
        );
    }
}
