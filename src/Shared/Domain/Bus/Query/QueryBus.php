<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

use App\Shared\Domain\Bus\Response;

interface QueryBus
{
    public function run(Query $query): ?Response;
}
