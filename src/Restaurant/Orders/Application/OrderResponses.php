<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application;

use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Shared\Domain\Bus\Response;

final class OrderResponses implements Response
{
    /**
     * @var array<OrderResponse>
     */
    public array $orders;

    public function __construct(OrderResponse ...$orders)
    {
        $this->orders = $orders;
    }

    /**
     * @param array<AbstractOrder> $orders
     */
    public static function fromOrdersArray(array $orders): self
    {
        return new self(...array_map(static fn(AbstractOrder $order) => OrderResponse::fromOrder($order), $orders));
    }
}
