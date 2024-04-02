<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use JsonSerializable;
use Money\Money as MoneyPhp;
use Money\Currency as CurrencyPhp;

/** @psalm-consistent-constructor */
class Money implements ValueObject, JsonSerializable
{
    private const DEFAULT_ROUND = MoneyPhp::ROUND_HALF_UP;
    private MoneyPhp $money;
    private Currency $currency;

    public function __construct(string $amount, ?Currency $currency)
    {
        $this->currency = $currency ?: Currency::euro();
        $this->money = new MoneyPhp($amount, new CurrencyPhp($this->currency->value()));
    }

    public function toString(): string
    {
        return $this->value() . $this->currency()->value();
    }

    public function value(): int
    {
        return $this->getAmount();
    }

    /**
     * @return array{amount: int, currency: string}
     */
    public function values(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->currency()->value(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->values();
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): int
    {
        return (int)$this->money->getAmount();
    }

    public function add(Money ...$addends): self
    {
        $newMoney = $this->money;

        foreach ($addends as $addend) {
            $newMoney = $newMoney->add($addend->money);
        }

        return new static($newMoney->getAmount(), $this->currency);
    }

    public function subtract(Money ...$subtrahends): self
    {
        $newMoney = $this->money;

        foreach ($subtrahends as $subtrahend) {
            $newMoney = $newMoney->subtract($subtrahend->money);
        }

        return new static($newMoney->getAmount(), $this->currency);
    }

    public function multiply(float|int|string $multiplier): self
    {
        $product = $this->money->multiply($multiplier, self::DEFAULT_ROUND);

        return new static($product->getAmount(), $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->money->equals($other->money);
    }

    public function lessThan(Money $other): bool
    {
        return $this->money->lessThan($other->money);
    }
}
