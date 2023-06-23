<?php

declare(strict_types=1);

namespace App\Core\REST\Exception;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiException
 */
class ApiException extends Exception
{
  /**
   * @param int                   $httpCode
   * @param string                $message
   * @param array<string, string> $headers
   * @param Throwable|null        $previous
   */
  protected function __construct(
    int $httpCode = Response::HTTP_SERVICE_UNAVAILABLE,
    string $message = '',
    protected array $headers = [],
    Throwable $previous = null
  ) {
    parent::__construct($message, $httpCode, $previous);
  }

  /**
   * @param string $message
   * @return self
   */
  public static function notFound(
    string $message
  ): self {
    return new self(
      Response::HTTP_NOT_FOUND,
      $message
    );
  }

  /**
   * @param string $message
   * @return self
   */
  public static function conflict(
    string $message
  ): self {
    return new self(
      Response::HTTP_CONFLICT,
      $message
    );
  }

  /**
   * @param string $message
   * @return self
   */
  public static function forbidden(
    string $message
  ): self {
    return new self(
      Response::HTTP_FORBIDDEN,
      $message
    );
  }

  /**
   * @return array<string, string>
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }
}
