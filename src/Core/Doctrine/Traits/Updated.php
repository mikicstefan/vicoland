<?php

namespace App\Core\Doctrine\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Exception;

/**
 * Trait Updated
 * Don't forget to add "#[HasLifecycleCallbacks]" attribute to your entity
 */
trait Updated
{
  #[Column(name: "updated", type: "datetime")]
  private DateTime $updated;

  /**
   * Gets triggered only on insert
   * @return void
   * @throws Exception
   */
  #[PrePersist]
  public function onPrePersist(): void
  {
    $this->updated = new DateTime();
  }

  /**
   * Gets triggered every time on update
   * @return void
   * @throws Exception
   */
  #[PreUpdate]
  public function onPreUpdate(): void
  {
    $this->updated = new DateTime();
  }

  /**
   * @return DateTime
   */
  public function getUpdated(): DateTime
  {
    return $this->updated;
  }

  /**
   * @param DateTime $updated
   * @return self
   */
  public function setUpdated(DateTime $updated): self
  {
    $this->updated = $updated;
    return $this;
  }
}
