<?php

namespace App\Core\Doctrine\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait UniversallyUniqueIdentifiers
 */
trait UniversallyUniqueIdentifiers
{
  /**
   * @var string
   */
  #[Assert\NotBlank]
  #[Column(name: "uuid", type: "uuid", unique: true, nullable: false)]
  private string $uuid;

  /**
   * @return string
   */
  public function getUuid(): string
  {
    return $this->uuid;
  }


  /**
   * @param string $uuid
   * @return $this
   */
  public function setUuid(string $uuid): static
  {
    $this->uuid = $uuid;
    return $this;
  }

  /**
   * Gets triggered only on insert
   * @return void
   * @SuppressWarnings(PHPMD)
   * @throws Exception
   */
  #[PrePersist]
  public function onPrePersistAddAttribute(): void
  {
    $this->uuid = (string) Uuid::uuid4();
  }
}
