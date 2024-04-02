<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Application\FindByCode;

use App\Shared\Domain\Bus\Query\Query;

final class FindByProductCodeQuery implements Query
{
    public function __construct(public readonly string $code)
    {
    }
}
