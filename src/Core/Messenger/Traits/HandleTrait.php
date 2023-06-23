<?php

declare(strict_types=1);

namespace App\Core\Messenger\Traits;

use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\LogicException;

/**
 * Trait HandleTrait
 */
trait HandleTrait
{
  protected MessageBusInterface $bus;

  /**
   * @param object $message The message or the message pre-wrapped in an envelope
   * @return mixed The handler returned value
   */
  protected function handle(object $message): mixed
  {
    if (!$this->bus instanceof MessageBusInterface) {
      throw new LogicException(
        sprintf(
          'You must provide a "%s" instance in the "%s::$bus" property, "%s" given.',
          MessageBusInterface::class,
          static::class,
          get_debug_type($this->bus)
        )
      );
    }

    $envelope = $this->bus->dispatch($message);

    /** @var HandledStamp[] $handledStamps */
    $handledStamps = $envelope->all(HandledStamp::class);

    if (empty($handledStamps)) {
      throw new LogicException(
        sprintf(
          'Message of type "%s" was handled zero times.'
                    . 'Exactly one handler is expected when using "%s::%s()".',
          get_debug_type($envelope->getMessage()),
          static::class,
          __FUNCTION__
        )
      );
    }

    if (count($handledStamps) > 1) {
      $handlers = implode(
        ', ',
        array_map(
          static fn (HandledStamp $stamp): string => sprintf('"%s"', $stamp->getHandlerName()),
          $handledStamps
        )
      );

      throw new LogicException(
        sprintf(
          'Message of type "%s" was handled multiple times.'
                    . 'Only one handler is expected when using "%s::%s()", got %d: %s.',
          get_debug_type($envelope->getMessage()),
          static::class,
          __FUNCTION__,
          \count($handledStamps),
          $handlers
        )
      );
    }

    return $handledStamps[0]->getResult();
  }
}
