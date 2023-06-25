<?php

namespace App\Core\REST\Subscriber;

use Assert\LazyAssertionException;
use App\Core\REST\Exception\ApiException;
use App\Core\REST\Exception\ResponseEncodingException;
use App\Core\REST\Representation\ExceptionRepresentation;
use App\Core\REST\Service\ApiSerializerService;
use App\Domain\Translation\ValueObject\ErrorMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

/**
 * Class ExceptionSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
  /**
   * @param ApiSerializerService $apiSerializerService
   */
  public function __construct(
    private ApiSerializerService $apiSerializerService
  ) {
  }

  /**
   * @return string[][]
   */
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::EXCEPTION => ['serializeOutput']
    ];
  }

  /**
   * @param ExceptionEvent $event
   * @return void
   * @throws ResponseEncodingException
   */
  public function serializeOutput(ExceptionEvent $event)
  {
    $attributes = $event->getRequest()->attributes;
    $exception = $event->getThrowable();

    while ($exception instanceof HandlerFailedException) {
      /** @var Throwable $exception */
      $exception = $exception->getPrevious();
    }

    $headers = [];

    /**
     * Put more specific exceptions on the top, and more general towards bottom
     */
    switch ($exception) {
      case $exception instanceof NotFoundHttpException:
        $representation = new ExceptionRepresentation(ErrorMessage::ERR_ENDPOINT_NOT_FOUND);
        $code = Response::HTTP_NOT_FOUND;
        break;

      case $exception instanceof MethodNotAllowedException:
        $representation = new ExceptionRepresentation(ErrorMessage::ERR_METHOD_NOT_ALLOWED);
        $code = Response::HTTP_METHOD_NOT_ALLOWED;
        break;

      case $exception instanceof AccessDeniedException:
        $representation = new ExceptionRepresentation(ErrorMessage::ERR_ACCESS_DENIED);
        $code = Response::HTTP_FORBIDDEN;
        break;

      case $exception instanceof HttpException:
        $representation = new ExceptionRepresentation($exception->getMessage());
        $code = Response::HTTP_FORBIDDEN;
        break;

      case $exception instanceof BadRequestException:
      case $exception instanceof LazyAssertionException:
        $representation = new ExceptionRepresentation($exception->getMessage());
        $code = Response::HTTP_BAD_REQUEST;
        break;

      case $exception instanceof ApiException:
        $representation = new ExceptionRepresentation($exception->getMessage());
        $code = $exception->getCode();
        $headers += $exception->getHeaders();
        break;

      default:
        $representation = new ExceptionRepresentation('Unhandled exception.');
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    $representation
      ->setDescription($exception->getMessage());

    // in prod environment we do not want to see line where exception occur and exception class
    if ($_ENV['APP_ENV'] != 'prod') {
      $representation
        ->setExceptionClass(get_class($exception))
        ->setLine($exception->getLine());
    }

    $contentType = $attributes->get('ContentType', 'application/json');
    $contentTypeFormat = strval($attributes->get('ContentTypeFormat', 'json'));
    $contentTypeEncoding = $attributes->get('ContentEncoding', 'identity');

    $body = $this->apiSerializerService->serialize($representation, $contentTypeFormat);

    $response = new Response(
      $body,
      $code,
      ['Content-Type' => $contentType, 'Content-Encoding' => $contentTypeEncoding] + $headers
    );

    $event->setResponse($response);
    $event->stopPropagation();
  }
}
