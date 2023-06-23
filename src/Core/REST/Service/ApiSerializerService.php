<?php

declare(strict_types=1);

namespace App\Core\REST\Service;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use App\Core\REST\Exception\ResponseEncodingException;
use App\Core\REST\Representation\ExceptionRepresentation;

/**
 * Class ApiSerializerService
 * Handles serializing and encoding the representations
 */
final class ApiSerializerService
{
  /**
   * @param SerializerInterface $serializer
   */
  public function __construct(
    private SerializerInterface $serializer
  ) {
  }

  /**
   * @param object   $representation
   * @param string   $format
   * @param string[] $groups
   * @return string
   * @throws ResponseEncodingException
   */
  public function serialize(
    object $representation,
    string $format,
    array $groups = []
  ): string {
    $context = SerializationContext::create();
    if (!empty($groups)) {
      $groups[] = 'Default';
      $context->setGroups($groups);
    }

    /*
     * we do not want to serialize null values in production environment when response is an exception (we want to
     * return only property of ExceptionRepresentation that are relevant for production)
     */
    $context->setSerializeNull($_ENV['APP_ENV'] != 'prod' || !$representation instanceof ExceptionRepresentation);

    $body = $this->serializer->serialize(
      $representation,
      $format,
      $context
    );

    if ($body == false) {
      throw new ResponseEncodingException();
    }

    return $body;
  }
}
