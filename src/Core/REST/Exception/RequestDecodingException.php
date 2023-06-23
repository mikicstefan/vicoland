<?php

declare(strict_types=1);

namespace App\Core\REST\Exception;

use Symfony\Component\HttpFoundation\Response;
use App\Domain\Translation\ValueObject\ErrorMessage;

/**
 * Class RequestDecodingException
 */
class RequestDecodingException extends ApiException
{
  /**
   * RequestDecodingException constructor.
   */
  public function __construct()
  {
    parent::__construct(
      Response::HTTP_BAD_REQUEST,
      ErrorMessage::ERR_REQUEST_DECODING_FAILED
    );
  }
}
