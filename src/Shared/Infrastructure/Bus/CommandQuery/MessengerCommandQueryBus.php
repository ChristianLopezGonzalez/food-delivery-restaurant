<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\CommandQuery;

use App\Shared\Domain\Bus\CommandQuery\CommandQuery;
use App\Shared\Domain\Bus\CommandQuery\CommandQueryBus;
use App\Shared\Domain\Bus\Response;
use App\Shared\Infrastructure\Bus\MessengerEnvelopExtractorTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerCommandQueryBus implements CommandQueryBus
{
    use MessengerEnvelopExtractorTrait;

    private $commandQueryBus;

    public function __construct(MessageBusInterface $commandQueryBus)
    {
        $this->commandQueryBus = $commandQueryBus;
    }

    /**
     * @throws Throwable
     */
    public function run(CommandQuery $command): ?Response
    {
        try {
            $envelope = $this->commandQueryBus->dispatch($command);
            return $this->extractResultFromEnvelope($envelope);
        } catch (HandlerFailedException $exception) {
            throw $this->unwrapHandlerFailedExceptions($exception);
        }
    }
}
