<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application\CreateMenu;

use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Restaurant\Orders\Domain\Errors\DeliveryOrderRequiresEqualTotalPrice;
use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\PickUpOrderRequiresReachTotalPrice;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Restaurant\Orders\Domain\OrderId;
use App\Restaurant\Orders\Domain\OrderRepository;
use App\Restaurant\Orders\Domain\OrderWithPickUp;
use App\Restaurant\Orders\Domain\OrderWithDelivery;
use App\Restaurant\Orders\Domain\OrderLine;
use App\Restaurant\Orders\Domain\OrderLineAmount;
use App\Restaurant\Orders\Domain\OrderLineConcept;
use App\Restaurant\Orders\Domain\OrderLines;
use App\Restaurant\Orders\Domain\Service\DeliveryPrice\DeliveryPriceService;
use App\Restaurant\Orders\Domain\Service\ProductPrice\ProductPriceService;
use App\Shared\Application\UseCase;
use App\Shared\Domain\ValueObject\Price;

final class CreateMenuOrder implements UseCase
{
    private const DRINK = 'drink';

    public function __construct(
        private readonly ProductPriceService $productPriceService,
        private readonly DeliveryPriceService $deliveryPriceService,
        private readonly OrderRepository $repository
    ) {
    }

    /**
     * @throws DeliveryOrderRequiresEqualTotalPrice
     * @throws PickUpOrderRequiresReachTotalPrice
     * @throws RequiredConceptsNotFound
     * @throws LineItemAmountIsNotBetweenRequiredValues
     */
    public function __invoke(
        string $id,
        string $productCode,
        int $drinksAmount,
        bool $isDelivery,
        float $totalPrice
    ): AbstractOrder {
        $orderId = new OrderId($id);
        $orderLines = $this->createOrderLines($productCode, $drinksAmount);
        $totalOrderPrice = Price::fromFloat($totalPrice);

        $order = $this->createOrder($orderId, $orderLines, $totalOrderPrice, $isDelivery);
        $this->repository->create($order);

        return $order;
    }

    /**
     * @throws LineItemAmountIsNotBetweenRequiredValues
     * @throws RequiredConceptsNotFound
     * @throws PickUpOrderRequiresReachTotalPrice
     * @throws DeliveryOrderRequiresEqualTotalPrice
     */
    private function createOrder(OrderId $id, OrderLines $lines, Price $totalPrice, bool $isDelivery): AbstractOrder
    {
        if (!$isDelivery) {
            return OrderWithPickUp::create($id, $lines, $totalPrice);
        }

        $deliveryPrice = $this->deliveryPriceService->__invoke();
        return OrderWithDelivery::create($id, $lines, $deliveryPrice, $totalPrice);
    }

    private function createOrderLines($productCode, $drinksAmount): OrderLines
    {
        return new OrderLines(
            $this->createOrderLine($productCode),
            $this->createOrderLine(self::DRINK, $drinksAmount),
        );
    }

    private function createOrderLine(string $productCode, int $amount = 1): OrderLine
    {
        $lineDescription = new OrderLineConcept($productCode);
        $linePrice = $this->productPriceService->__invoke($productCode);
        $lineAmount = new OrderLineAmount($amount);

        return new OrderLine($lineDescription, $linePrice, $lineAmount);
    }
}
