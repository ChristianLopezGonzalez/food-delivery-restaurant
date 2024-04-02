<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Shared\Domain\ValueObject\ArrayValueObject;
use App\Shared\Domain\ValueObject\Price;

final class OrderLines extends ArrayValueObject
{
    public function __construct(OrderLine ...$orderLines)
    {
        parent::__construct($orderLines);
    }

    public function excludeAmountsLowerThanOne(): self
    {
        $nextOrderLines = array_filter($this->values, static function (OrderLine $orderLine) {
            return $orderLine->amount()->biggerThan(0);
        });

        return new self(...$nextOrderLines);
    }

    public function totalPrice(): Price
    {
        $totalPrice = Price::zero();

        /** @var array<OrderLine> $orderLines */
        $orderLines = $this->values;
        foreach ($orderLines as $orderLine) {
            $orderLinePrice = $orderLine->price()->multiply($orderLine->amount()->value());
            $totalPrice = $totalPrice->add($orderLinePrice);
        }

        return $totalPrice;
    }

    /**
     * @param array<array-key, string> $concepts
     * @return bool
     */
    public function contains(array $concepts): bool
    {
        /** @var array<OrderLine> $orderLines */
        $orderLines = $this->values;
        foreach ($orderLines as $orderLine) {
            if ($orderLine->concept()->in($concepts)) {
                return true;
            }
        }

        return false;
    }

    public function isConceptAmountBetween(string $concept, int $minAmount, int $maxAmount): bool
    {
        /** @var array<OrderLine> $orderLines */
        $orderLines = array_filter($this->values, static function (OrderLine $line) use ($concept) {
            return $line->concept()->in([$concept]);
        });

        foreach ($orderLines as $orderLine) {
            if (!$orderLine->amount()->between($minAmount, $maxAmount)) {
                return false;
            }
        }

        return true;
    }
}
