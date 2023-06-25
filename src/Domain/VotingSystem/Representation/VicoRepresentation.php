<?php

declare(strict_types=1);

namespace App\Domain\VotingSystem\Representation;

use App\Domain\VotingSystem\Entity\Vico;
use DateTime;

/**
 * Class VicoRepresentation
 */
class VicoRepresentation
{
  /**
   * @param int $id
   * @param string $name
   * @param DateTime $created
   */
  private function __construct(
    private int $id,
    private string $name,
    private DateTime $created
  ) {
  }

  /**
   * @param Vico $vico
   * @return self
   */
  public static function fromEntity(Vico $vico): self
  {
    return new self(
      $vico->getId(),
      $vico->getName(),
      $vico->getCreated()
    );
  }
}
