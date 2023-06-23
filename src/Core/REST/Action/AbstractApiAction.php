<?php

declare(strict_types=1);

namespace App\Core\REST\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Core\REST\ValueObject\ApiResponse;
use App\Core\Messenger\Traits\HandleTrait;

/**
 * Class AbstractApiAction
 */
abstract class AbstractApiAction
{
  use HandleTrait;

  /**
   * @param MessageBusInterface $bus
   */
  public function __construct(
    MessageBusInterface $bus,
  ) {
    $this->bus = $bus;
  }

  /**
   * @param Request $request
   * @return ApiResponse
   */
  abstract public function __invoke(Request $request): ApiResponse;
}
