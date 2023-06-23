<?php

declare(strict_types=1);

namespace App\Core\REST\Exception;

use Symfony\Component\HttpFoundation\Response;
use App\Domain\Translation\ValueObject\ErrorMessage;

/**
 * Class ResponseEncodingException
 */
class ResponseEncodingException extends ApiException
{
  /**
   * ResponseEncodingException constructor.
   */
  public function __construct()
  {
    parent::__construct(
      Response::HTTP_INTERNAL_SERVER_ERROR,
      ErrorMessage::ERR_RESPONSE_ENCODING_FAILED
    );
  }
}
