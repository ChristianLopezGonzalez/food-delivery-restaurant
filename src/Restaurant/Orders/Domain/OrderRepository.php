<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

interface OrderRepository
{
    public function create(AbstractOrder $order): void;

    /**
     * @return array<AbstractOrder>
     */
    public function searchOrderByType(OrderType $type): array;
}
