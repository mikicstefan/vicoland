<?php

declare(strict_types=1);

namespace App\Domain\Translation\ValueObject;

/**
 * Class ErrorMessage
 * Encapsulates all error messages
 */
final class ErrorMessage
{
  /**
   * Should not be instantiated
   */
  private function __construct()
  {
  }

  // region Decoding and Encoding Error Messages
  public const ERR_REQUEST_DECODING_FAILED = 'ERR_REQUEST_DECODING_FAILED';
  public const ERR_RESPONSE_ENCODING_FAILED = 'ERR_RESPONSE_ENCODING_FAILED';
  public const ERR_REQUEST_BODY_DECODING_ERROR = 'ERR_REQUEST_BODY_DECODING_ERROR';
  public const ERR_UNACCEPTABLE_REQUEST_BODY_TYPE = 'ERR_UNACCEPTABLE_REQUEST_BODY_TYPE';
  // endregion Decoding and Encoding Error Messages

  // region General Error Messages
  public const ERR_PAGE_NUMBER_INVALID = 'ERR_PAGE_NUMBER_INVALID';
  public const ERR_PAGE_NUMBER_MUST_BE_NUMERIC = 'ERR_PAGE_NUMBER_MUST_BE_NUMERIC';
  public const ERR_PAGE_OFFSET_INVALID = 'ERR_PAGE_OFFSET_INVALID';
  public const ERR_PAGE_OFFSET_MUST_BE_NUMERIC = 'ERR_PAGE_OFFSET_MUST_BE_NUMERIC';
  public const ERR_PAGE_PROVIDE_EITHER_OFFSET_OR_NUMBER = 'ERR_PAGE_PROVIDE_EITHER_OFFSET_OR_NUMBER';
  public const ERR_PAGE_SIZE_MUST_BE_INTEGER = 'ERR_PAGE_SIZE_MUST_BE_INTEGER';
  public const ERR_PAGE_SIZE_INVALID = 'ERR_PAGE_SIZE_INVALID';
  public const ERR_ENTITY_NOT_FOUND = '%s not found';
  // region General Error Messages

  // region Endpoint Error Messages
  public const ERR_ENDPOINT_NOT_FOUND = 'ERR_ENDPOINT_NOT_FOUND';
  public const ERR_METHOD_NOT_ALLOWED = 'ERR_METHOD_NOT_ALLOWED';
  public const ERR_ACCESS_DENIED = 'ERR_ACCESS_DENIED';
  // region Endpoint Error Messages
}
