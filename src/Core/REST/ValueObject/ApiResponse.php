<?php

declare(strict_types=1);

namespace App\Core\REST\ValueObject;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Core\REST\Representation\BlankRepresentation;

/**
 * Class ApiResponse
 * Encapsulates the headers, response code and response body representation
 */
final class ApiResponse
{
  /**
   * ApiResponse constructor.
   * @param object                $representation
   * @param int                   $httpCode
   * @param array<string, string> $headers
   * @param Cookie[]              $cookies
   * @param string[]              $destroyCookies
   */
  public function __construct(
    protected object $representation,
    protected int $httpCode = Response::HTTP_OK,
    protected array $headers = [],
    protected array $cookies = [],
    protected array $destroyCookies = []
  ) {
  }

  /**
   * Use for short-hand return an object that was just created
   * @param object|null           $representation
   * @param array<string, string> $headers
   * @param Cookie[]              $cookies
   * @param string[]              $destroyCookies
   * @return ApiResponse
   */
  public static function created(
    object|null $representation = null,
    array $headers = [],
    array $cookies = [],
    array $destroyCookies = []
  ): self {
    return new self(
      $representation ?? new BlankRepresentation(),
      Response::HTTP_CREATED,
      $headers,
      $cookies,
      $destroyCookies
    );
  }

  /**
   * Use for short-hand return of Representations
   * @param object|null           $representation
   * @param array<string, string> $headers
   * @param Cookie[]              $cookies
   * @param string[]              $destroyCookies
   * @return ApiResponse
   */
  public static function success(
    object $representation = null,
    array $headers = [],
    array $cookies = [],
    array $destroyCookies = []
  ): self {
    return new self(
      $representation ?? new BlankRepresentation(),
      Response::HTTP_OK,
      $headers,
      $cookies,
      $destroyCookies
    );
  }

  /**
   * User for short-hand creation of redirection responses
   * @param string   $url
   * @param Cookie[] $cookies
   * @param string[] $destroyCookies
   * @return ApiResponse
   */
  public static function redirect(
    string $url,
    array $cookies = [],
    array $destroyCookies = []
  ): self {
    return new self(
      new BlankRepresentation(),
      Response::HTTP_FOUND,
      ['Location' => $url],
      $cookies,
      $destroyCookies
    );
  }

  /**
   * Use for short-hand return of conflict responses
   * @param object|null           $representation
   * @param array<string, string> $headers
   * @param Cookie[]              $cookies
   * @param string[]              $destroyCookies
   * @return ApiResponse
   */
  public static function conflict(
    object $representation = null,
    array $headers = [],
    array $cookies = [],
    array $destroyCookies = []
  ): self {
    return new self(
      $representation ?? new BlankRepresentation(),
      Response::HTTP_CONFLICT,
      $headers,
      $cookies,
      $destroyCookies
    );
  }

  /**
   * @return object
   */
  public function representation(): object
  {
    return $this->representation;
  }

  /**
   * @param int $httpCode
   * @return ApiResponse
   */
  public function setHttpCode(int $httpCode): self
  {
    $this->httpCode = $httpCode;
    return $this;
  }

  /**
   * @return int
   */
  public function httpCode(): int
  {
    return $this->httpCode;
  }

  /**
   * @param string $name
   * @param string $value
   * @return ApiResponse
   */
  public function addHeader(string $name, string $value): self
  {
    $this->headers[$name] = $value;
    return $this;
  }

  /**
   * @return array<string, string>
   */
  public function headers(): array
  {
    return $this->headers;
  }

  /**
   * @return Cookie[]
   */
  public function cookies(): array
  {
    return $this->cookies;
  }

  /**
   * @return string[]
   */
  public function destroyCookies(): array
  {
    return $this->destroyCookies;
  }
}
