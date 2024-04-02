<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application;

use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Restaurant\Orders\Domain\OrderLine;
use App\Shared\Domain\Bus\Response;

final class OrderResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly bool $hasDrink,
        public readonly array $lines,
        public readonly float $deliveryPrice,
        public readonly float $totalPrice,
        public readonly string $currency
    ) {
    }

    public static function fromOrder(AbstractOrder $order): self
    {
        $hasDrink = $order->lines()->contains(['drink']);

        $lines = array_map(static fn(OrderLine $line) => [
            'concept' => $line->concept()->value(),
            'amount' => $line->amount()->value(),
            'price' => $line->price()->toFloat(),
            'totalPrice' => $line->price()->multiply($line->amount()->value())->toFloat(),
        ], $order->lines()->value());

        return new self(
            $order->id()->value(),
            $order->type()->value(),
            $hasDrink,
            $lines,
            $order->deliveryPrice()->toFloat(),
            $order->totalPrice()->toFloat(),
            $order->totalPrice()->currency()->value(),
        );
    }
}
