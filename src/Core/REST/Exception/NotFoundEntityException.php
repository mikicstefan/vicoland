<?php

declare(strict_types=1);

namespace App\Core\REST\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnsupportedRequestException
 */
class NotFoundEntityException extends ApiException
{
  /**
   * UnsupportedRequestException constructor.
   * @param string $message
   */
  public function __construct(string $message)
  {
    parent::__construct(
      Response::HTTP_NOT_FOUND,
      $message
    );
  }
}
