<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Price;

abstract class AbstractOrder extends AggregateRoot
{
    private const REQUIRED_CONCEPTS = ['pizza', 'burger', 'sushi'];
    private const DRINK_CONCEPT = 'drink';
    private const MIN_DRINK_AMOUNT = 0;
    private const MAX_DRINK_AMOUNT = 2;

    protected function __construct(
        readonly public OrderId $id,
        readonly private OrderType $type,
        private OrderLines $lines,
        readonly private Price $deliveryPrice,
        readonly private Price $totalPrice
    ) {
        $this->lines = $lines->excludeAmountsLowerThanOne();
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function type(): OrderType
    {
        return $this->type;
    }

    public function lines(): OrderLines
    {
        return $this->lines;
    }

    public function deliveryPrice(): Price
    {
        return $this->deliveryPrice;
    }

    public function totalPrice(): Price
    {
        return $this->totalPrice;
    }

    /**
     * @throws RequiredConceptsNotFound
     * @throws LineItemAmountIsNotBetweenRequiredValues
     */
    public static function validateOrderLines(OrderLines $orderLines): void
    {
        $contains = self::REQUIRED_CONCEPTS;
        if (!$orderLines->contains($contains)) {
            throw new RequiredConceptsNotFound($contains);
        }

        $concept = self::DRINK_CONCEPT;
        $min = self::MIN_DRINK_AMOUNT;
        $max = self::MAX_DRINK_AMOUNT;
        if (!$orderLines->isConceptAmountBetween($concept, $min, $max)) {
            throw new LineItemAmountIsNotBetweenRequiredValues($concept, $min, $max);
        }
    }
}
