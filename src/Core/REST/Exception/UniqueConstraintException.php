<?php

namespace App\Core\REST\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UniqueConstraintException
 */
class UniqueConstraintException extends ApiException
{
  /**
   * UniqueConstraintException constructor.
   * @param string $message
   * @param int $statusCode
   */
  public function __construct(string $message, int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY)
  {
    parent::__construct(
      $statusCode,
      $message
    );
  }
}
