<?php

namespace App\Core\Doctrine\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Exception;

/**
 * Trait Timestampable
 * Don't forget to add "#[HasLifecycleCallbacks]" attribute to your entity
 */
trait Timestampable
{
  #[Column(name: "created_at", type: "datetime")]
  private DateTime $createdAt;

  #[Column(name: "updated_at", type: "datetime")]
  private DateTime $updatedAt;

  /**
   * Gets triggered only on insert
   * @return void
   * @throws Exception
   */
  #[PrePersist]
  public function onPrePersist(): void
  {
    $this->createdAt = new DateTime();
    $this->updatedAt = new DateTime();
  }

  /**
   * Gets triggered every time on update
   * @return void
   * @throws Exception
   */
  #[PreUpdate]
  public function onPreUpdate(): void
  {
    $this->updatedAt = new DateTime();
  }

  /**
   * @return DateTime
   */
  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  /**
   * @param DateTime $createdAt
   * @return self
   */
  public function setCreatedAt(DateTime $createdAt): self
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  /**
   * @return DateTime
   */
  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  /**
   * @param DateTime $updatedAt
   * @return self
   */
  public function setUpdatedAt(DateTime $updatedAt): self
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }
}
