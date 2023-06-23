<?php

declare(strict_types=1);

namespace App\Core\REST\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnprocessableEntityException
 */
class UnprocessableEntityException extends ApiException
{
  /**
   * UnprocessableEntityException constructor.
   */
  public function __construct($message)
  {
    parent::__construct(
      Response::HTTP_UNPROCESSABLE_ENTITY,
      $message
    );
  }
}
