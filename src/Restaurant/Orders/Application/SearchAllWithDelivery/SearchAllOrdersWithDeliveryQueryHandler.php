<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application\SearchAllWithDelivery;

use App\Restaurant\Orders\Application\OrderResponses;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class SearchAllOrdersWithDeliveryQueryHandler implements QueryHandler
{
    public function __construct(private readonly SearchAllOrdersWithDelivery $searcher)
    {
    }

    public function __invoke(SearchAllOrdersWithDeliveryQuery $menuOrder): OrderResponses
    {
        $orders = $this->searcher->__invoke();

        return OrderResponses::fromOrdersArray($orders);
    }
}
