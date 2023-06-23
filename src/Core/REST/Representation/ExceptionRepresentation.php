<?php

namespace App\Core\REST\Representation;

/**
 * Class ExceptionRepresentation
 */
class ExceptionRepresentation
{
  /**
   * @param string $message
   * @param string|null $exceptionClass
   * @param int|null $line
   * @param string|null $description
   * @param array<string> $errors
   */
  public function __construct(
    private string $message,
    private ?string $exceptionClass = null,
    private ?int $line = null,
    private ?string $description = null,
    private array $errors = []
  ) {
  }

  /**
   * @param string|null $exceptionClass
   * @return ExceptionRepresentation
   */
  public function setExceptionClass(?string $exceptionClass): ExceptionRepresentation
  {
    $this->exceptionClass = $exceptionClass;
    return $this;
  }

  /**
   * @param int|null $line
   * @return ExceptionRepresentation
   */
  public function setLine(?int $line): ExceptionRepresentation
  {
    $this->line =  $line;
    return $this;
  }

  /**
   * @param string|null $description
   * @return ExceptionRepresentation
   */
  public function setDescription(?string $description): ExceptionRepresentation
  {
    $this->description = $description;
    return $this;
  }

  /**
   * @param array<string> $errors
   * @return ExceptionRepresentation
   */
  public function setErrors(array $errors): ExceptionRepresentation
  {
    $this->errors = $errors;
    return $this;
  }
}
