<?php

namespace App\Domain\VotingSystem\Entity;

use App\Core\Doctrine\Traits\Identity;
use App\Core\Doctrine\Traits\Created;
use App\Domain\VotingSystem\Repository\VicoRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vico
 */
#[Table(name: "vico")]
#[Entity(repositoryClass: VicoRepository::class)]
#[HasLifecycleCallbacks]
class Vico
{
  use Identity;
  use Created;

  #[Column(name: "name", type: "string", length: 64)]
  #[Assert\NotBlank]
  private string $name;

  // Getters and Setters start
  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Vico
   */
  public function setName(string $name): Vico
  {
    $this->name = $name;
    return $this;
  }
  // Getters and Setters end
}
