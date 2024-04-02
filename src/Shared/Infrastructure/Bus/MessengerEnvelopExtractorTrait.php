<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

use function count;

trait MessengerEnvelopExtractorTrait
{
    /**
     * @see \Symfony\Component\Messenger\HandleTrait
     */
    private function extractResultFromEnvelope(Envelope $envelope): ?Response
    {
        /** @var array<HandledStamp> $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new LogicException(
                sprintf(
                    'Message of type "%s" was handled zero times. 
                    Exactly one handler is expected when using "%s::%s()".',
                    get_debug_type($envelope->getMessage()),
                    self::class,
                    __FUNCTION__,
                ),
            );
        }

        if (count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(static function (HandledStamp $stamp): string {
                return sprintf('"%s"', $stamp->getHandlerName());
            }, $handledStamps));

            throw new LogicException(
                sprintf(
                    'Message of type "%s" was handled multiple times. 
                    Only one handler is expected when using "%s::%s()", got %d: %s.',
                    get_debug_type($envelope->getMessage()),
                    self::class,
                    __FUNCTION__,
                    count($handledStamps),
                    $handlers,
                ),
            );
        }

        /** @var Response|null */
        return $handledStamps[0]->getResult();
    }

    private function unwrapHandlerFailedExceptions(HandlerFailedException $exception): Throwable
    {
        do {
            if (!$exception instanceof HandlerFailedException) {
                return $exception;
            }
        } while ($exception = $exception->getPrevious());

        return $exception;
    }
}
