<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application\SearchAllWithDelivery;

use App\Restaurant\Orders\Domain\OrderRepository;
use App\Restaurant\Orders\Domain\OrderType;
use App\Restaurant\Orders\Domain\OrderWithDelivery;
use App\Shared\Application\UseCase;

final class SearchAllOrdersWithDelivery implements UseCase
{
    public function __construct(private readonly OrderRepository $repository)
    {
    }

    /**
     * @return array<OrderWithDelivery>
     */
    public function __invoke(): array
    {
        return $this->repository->searchOrderByType(OrderType::createDelivery());
    }
}
