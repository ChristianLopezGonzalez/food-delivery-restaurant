<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application\CreateMenu;

use App\Restaurant\Orders\Application\OrderResponse;
use App\Restaurant\Orders\Domain\Errors\DeliveryOrderRequiresEqualTotalPrice;
use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\PickUpOrderRequiresReachTotalPrice;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Shared\Domain\Bus\CommandQuery\CommandQueryHandler;

final class CreateMenuOrderCommandQueryHandler implements CommandQueryHandler
{
    public function __construct(private readonly CreateMenuOrder $creator)
    {
    }

    /**
     * @throws LineItemAmountIsNotBetweenRequiredValues
     * @throws RequiredConceptsNotFound
     * @throws DeliveryOrderRequiresEqualTotalPrice
     * @throws PickUpOrderRequiresReachTotalPrice
     */
    public function __invoke(CreateMenuOrderCommandQuery $menuOrder): OrderResponse
    {
        $order = $this->creator->__invoke(
            $menuOrder->id,
            $menuOrder->productCode,
            $menuOrder->drinksQty,
            $menuOrder->isDelivery,
            $menuOrder->amount,
        );

        return OrderResponse::fromOrder($order);
    }
}
