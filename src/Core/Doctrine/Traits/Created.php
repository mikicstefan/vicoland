<?php

namespace App\Core\Doctrine\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Exception;

/**
 * Trait Created
 * Don't forget to add "#[HasLifecycleCallbacks]" attribute to your entity
 */
trait Created
{
  #[Column(name: "created", type: "datetime")]
  private DateTime $created;

  /**
   * Gets triggered only on insert
   * @return void
   * @throws Exception
   */
  #[PrePersist]
  public function onPrePersist(): void
  {
    $this->created = new DateTime();
  }

  /**
   * @return DateTime
   */
  public function getCreated(): DateTime
  {
    return $this->created;
  }

  /**
   * @param DateTime $created
   * @return self
   */
  public function setCreated(DateTime $created): self
  {
    $this->created = $created;
    return $this;
  }
}
