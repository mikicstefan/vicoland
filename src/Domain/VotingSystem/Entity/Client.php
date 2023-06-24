<?php

namespace App\Domain\VotingSystem\Entity;

use App\Core\Doctrine\Traits\Identity;
use App\Core\Doctrine\Traits\Created;
use App\Domain\VotingSystem\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Client
 */
#[Table(name: "client")]
#[Entity(repositoryClass: ClientRepository::class)]
#[HasLifecycleCallbacks]
class Client
{
  use Identity;
  use Created;

  #[Column(name: "username", type: "string", length: 128, unique: true)]
  #[Assert\NotBlank]
  #[Assert\Email]
  private string $username;

  #[Column(name: "password", type: "string", length: 96)]
  #[Assert\NotBlank]
  private string $password;

  #[Column(name: "first_name", type: "string", length: 96)]
  #[Assert\NotBlank]
  private string $firstName;

  #[Column(name: "last_name", type: "string", length: 96)]
  #[Assert\NotBlank]
  private string $lastName;

  /**
   * Client can have many projects
   *
   * @var ArrayCollection|Collection
   */
  #[OneToMany(mappedBy: "creator", targetEntity: Project::class)]
  private Collection|ArrayCollection $projects;

  public function __construct()
  {
    $this->projects = new ArrayCollection();
  }

  // Getters and Setters start
  /**
   * @return string
   */
  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * @param string $username
   * @return Client
   */
  public function setUsername(string $username): Client
  {
    $this->username = $username;
    return $this;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * @param string $password
   * @return Client
   */
  public function setPassword(string $password): Client
  {
    $this->password = $password;
    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   * @return Client
   */
  public function setFirstName(string $firstName): Client
  {
    $this->firstName = $firstName;
    return $this;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   * @return Client
   */
  public function setLastName(string $lastName): Client
  {
    $this->lastName = $lastName;
    return $this;
  }

  /**
   * @return ArrayCollection|Collection
   */
  public function getProjects(): ArrayCollection|Collection
  {
    return $this->projects;
  }

  /**
   * @param ArrayCollection|Collection $projects
   * @return Client
   */
  public function setProjects(ArrayCollection|Collection $projects): Client
  {
    $this->projects = $projects;
    return $this;
  }
  // Getters and Setters end
}
