<?php

namespace App\Core\Doctrine\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * Trait Identity
 */
trait Identity
{
  #[Id()]
  #[GeneratedValue()]
  #[Column(name: "id", type: "integer")]
  private int $id;

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }
}
