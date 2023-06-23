<?php

declare(strict_types=1);

namespace App\Core\REST\Subscriber;

use JsonException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Domain\Translation\ValueObject\ErrorMessage;
use App\Core\REST\Exception\RequestDecodingException;
use App\Core\REST\Exception\UnsupportedRequestException;

/**
 * Class RequestSubscriber
 */
final class RequestSubscriber implements EventSubscriberInterface
{
  /**
   * The events to listen to
   * @return array<string, array<array-key, string|int>>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::REQUEST => [
        'parse', 254,
      ],
    ];
  }

  /**
   * Parses the incoming request
   * @param RequestEvent $event
   * @throws UnsupportedRequestException
   */
  public function parse(RequestEvent $event): void
  {
    $pathArr = explode('/', trim($event->getRequest()->getPathInfo(), '/'));
    $request = $event->getRequest();
    $headers = $request->headers;

    $contentType = $headers->get('Content-Type');

    /**
     * Handling logic that may be specific to different API versions
     */
    switch ($pathArr[0] ?? null) {
      case 'docs': // Swagger
        break;
      default:
        /** @var string $body */
        $body = $request->getContent();
        if (empty($body)) {
          return;
        }

        if ($contentType !== 'application/json') {
          throw new UnsupportedRequestException(
            ErrorMessage::ERR_UNACCEPTABLE_REQUEST_BODY_TYPE
          );
        }

        try {
          $body = json_decode(
            json: $body,
            associative: true,
            flags: JSON_THROW_ON_ERROR
          );
        } catch (JsonException) {
          throw new UnsupportedRequestException(
            ErrorMessage::ERR_REQUEST_BODY_DECODING_ERROR
          );
        }
          $request->request->replace(is_array($body) ? $body : [$body]);
        break;
    }
  }
}
