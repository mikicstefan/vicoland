<?php

declare(strict_types=1);

namespace App\Core\REST\ValueObject;

use Assert\Assert;
use App\Domain\Translation\ValueObject\ErrorMessage;
use Symfony\Component\HttpFoundation\Request;

use function Assert\that;

/**
 * Class Pagination
 * Set pagination to given collection
 */
class Pagination
{
  private const NAME = 'page';

  /**
   * @param int|null $size
   * @param int|null $offset
   */
  public function __construct(
    public readonly int|null $size,
    public readonly int|null $offset
  ) {
  }

  /**
   * @param Request $request
   * @SuppressWarnings(PHPMD)
   * @return self
   */
  public static function fromRequest(Request $request): self
  {
    /** @var array<string, int> $data */
    $data = $request->query->all(self::NAME);

    $pageNumber = $data['number'] ?? null;
    $pageOffset = $data['offset'] ?? null;
    $pageSize = $data['size'] ?? null;

    $assert = Assert::lazy();

    $assert->that($pageOffset, self::class)
      ->nullOr()
      ->numeric(ErrorMessage::ERR_PAGE_OFFSET_MUST_BE_NUMERIC)
      ->greaterOrEqualThan(0, ErrorMessage::ERR_PAGE_OFFSET_INVALID);

    $assert->that($pageNumber, self::class)
      ->nullOr()
      ->numeric(ErrorMessage::ERR_PAGE_NUMBER_MUST_BE_NUMERIC)
      ->greaterOrEqualThan(1, ErrorMessage::ERR_PAGE_NUMBER_INVALID);

    $assert->that($pageOffset && $pageNumber, self::class)
      ->notSame(true, ErrorMessage::ERR_PAGE_PROVIDE_EITHER_OFFSET_OR_NUMBER);

    $assert->that($pageSize, self::class)
      ->nullOr()
      ->numeric(ErrorMessage::ERR_PAGE_SIZE_MUST_BE_INTEGER)
      ->greaterOrEqualThan(1, ErrorMessage::ERR_PAGE_SIZE_INVALID);


    $assert->verifyNow();

    if ($pageSize) {
      $pageSize = (int) $pageSize;
    }

    if ($pageNumber) {
      $pageNumber = (int) $pageNumber;
    }

    if ($pageOffset) {
      $pageOffset = (int) $pageOffset;
    }

    if ($pageSize && $pageNumber) {
      if ($pageOffset == 0) {
        $pageOffset = null;
      }

      $pageOffset = (int) ($pageOffset ?? ($pageSize * ($pageNumber - 1)));
    }

    return new self($pageSize, $pageOffset);
  }

  /**
   * @return int|null
   */
  public function size(): ?int
  {
    return $this->size;
  }

  /**
   * @return int|null
   */
  public function offset(): ?int
  {
    return $this->offset;
  }
}
