<?php

declare(strict_types=1);

namespace App\Core\REST\Subscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Core\REST\ValueObject\ApiResponse;
use App\Core\REST\Service\ApiSerializerService;
use App\Core\REST\Exception\ResponseEncodingException;

/**
 * Class ResponderSubscriber
 */
final class ResponderSubscriber implements EventSubscriberInterface
{
  /**
   * @param ApiSerializerService $serializer
   */
  public function __construct(
    private ApiSerializerService $serializer
  ) {
  }

  /**
   * The events to listen to
   * @return array<string, array<array-key, string>>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::VIEW => [
        'serialize',
      ],
    ];
  }

  /**
   * Serializes the Representation object to a REST JSON object
   * @param ViewEvent $event
   * @throws ResponseEncodingException
   */
  public function serialize(ViewEvent $event): void
  {
    /** @var ApiResponse $apiResponse */
    $apiResponse    = $event->getControllerResult();
    $attributes     = $event->getRequest()->attributes;

    $contentType        = $attributes->get('ContentType');

    $body = $this->serializer->serialize(
      $apiResponse->representation(),
      'json'
    );

    $response = new Response(
      $body,
      $apiResponse->httpCode(),
      array_merge($apiResponse->headers(), [
        'Content-Type'      => $contentType
      ])
    );

    foreach ($apiResponse->cookies() as $cookie) {
      $response->headers->setCookie($cookie);
    }

    foreach ($apiResponse->destroyCookies() as $cookieName) {
      $response->headers->clearCookie($cookieName);
    }

    $event->setResponse($response);
    $event->stopPropagation();
  }
}
