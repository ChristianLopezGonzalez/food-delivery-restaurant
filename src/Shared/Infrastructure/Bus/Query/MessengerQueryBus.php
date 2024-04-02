<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Response;
use App\Shared\Infrastructure\Bus\MessengerEnvelopExtractorTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerQueryBus implements QueryBus
{
    use MessengerEnvelopExtractorTrait;

    private $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @throws Throwable
     */
    public function run(Query $query): ?Response
    {
        try {
            $envelope = $this->queryBus->dispatch($query);
            return $this->extractResultFromEnvelope($envelope);
        } catch (HandlerFailedException $exception) {
            throw $this->unwrapHandlerFailedExceptions($exception);
        }
    }
}
