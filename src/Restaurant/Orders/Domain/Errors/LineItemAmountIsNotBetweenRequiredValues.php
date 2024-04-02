<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Errors;

use App\Shared\Domain\DomainError;

final class LineItemAmountIsNotBetweenRequiredValues extends DomainError
{
    public function __construct(private readonly string $concept, private readonly int $min, private readonly int $max)
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf('Number of "%s" should be between %d and %d.', $this->concept, $this->min, $this->max);
    }
}
