<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\CommandQuery;

use App\Shared\Domain\Bus\Response;

interface CommandQueryBus
{
    public function run(CommandQuery $command): ?Response;
}
