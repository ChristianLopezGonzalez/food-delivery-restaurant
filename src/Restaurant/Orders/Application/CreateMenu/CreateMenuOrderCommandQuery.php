<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Application\CreateMenu;

use App\Shared\Domain\Bus\CommandQuery\CommandQuery;

final class CreateMenuOrderCommandQuery implements CommandQuery
{
    public function __construct(
        public readonly string $id,
        public readonly string $productCode,
        public readonly float $amount,
        public readonly bool $isDelivery,
        public readonly int $drinksQty
    ) {
    }
}
