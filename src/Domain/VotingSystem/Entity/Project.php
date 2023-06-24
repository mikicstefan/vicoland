<?php

namespace App\Domain\VotingSystem\Entity;

use App\Core\Doctrine\Traits\Identity;
use App\Core\Doctrine\Traits\Created;
use App\Core\Doctrine\Traits\Updated;
use App\Domain\VotingSystem\Repository\ProjectRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Project
 */
#[Table(name: "project")]
#[Entity(repositoryClass: ProjectRepository::class)]
#[HasLifecycleCallbacks]
class Project
{
  use Identity;
  use Created;
  use Updated;

  #[Assert\NotBlank]
  #[Column(name: "title", type: "string", length: 255)]
  private string $title;

  /**
   * Project can have only one client
   *
   * @var Client
   */
  #[ManyToOne(targetEntity: Client::class, inversedBy: 'projects')]
  #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
  private Client $creator;

  /**
   * Project can have only one vico
   *
   * @var Vico|null
   */
  #[ManyToOne(targetEntity: Vico::class, inversedBy: 'projects')]
  #[JoinColumn(name: 'vico_id', referencedColumnName: 'id')]
  private ?Vico $vico = null;

  /**
   * @var int|null
   */
  #[Assert\NotBlank]
  #[Assert\Range(min: 1, max: 5)]
  #[Column(name: "rating", type: "integer", nullable: true)]
  private ?int $rating = null;

  /**
   * @var int|null
   */
  #[Assert\NotBlank]
  #[Column(name: "review", type: "text", nullable: true)]
  private $review = null;

  /**
   * @var int|null
   */
  #[Assert\NotBlank]
  #[Assert\Range(min: 1, max: 5)]
  #[Column(name: "communication_rating", type: "integer", nullable: true)]
  private ?int $communicationRating = null;

  /**
   * @var int|null
   */
  #[Assert\NotBlank]
  #[Assert\Range(min: 1, max: 5)]
  #[Column(name: "quality_of_work_rating", type: "integer", nullable: true)]
  private ?int $qualityOfWorkRating = null;

  /**
   * @var int|null
   */
  #[Assert\NotBlank]
  #[Assert\Range(min: 1, max: 5)]
  #[Column(name: "value_for_money_rating", type: "integer", nullable: true)]
  private ?int $valueForMoneyRating = null;

  // Getters and Setters start
  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $title
   * @return Vico
   */
  public function setTitle(string $title): Project
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return Client
   */
  public function getCreator(): Client
  {
    return $this->creator;
  }

  /**
   * @param Client $creator
   * @return Project
   */
  public function setCreator(Client $creator): Project
  {
    $this->creator = $creator;
    return $this;
  }

  /**
   * @return Vico|null
   */
  public function getVico(): ?Vico
  {
    return $this->vico;
  }

  /**
   * @param Vico|null $vico
   * @return Project
   */
  public function setVico(?Vico $vico): Project
  {
    $this->vico = $vico;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getRating(): ?int
  {
    return $this->rating;
  }

  /**
   * @param int|null $rating
   * @return Project
   */
  public function setRating(?int $rating): Project
  {
    $this->rating = $rating;
    return $this;
  }

  public function getReview()
  {
    return $this->review;
  }

  /**
   * @param $review
   * @return Project
   * @throws Exception
   */
  public function setReview($review = null): Project
  {
    if (!is_string($review)) {
      throw new Exception('Provided value is not text');
    }

    $this->review = $review;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getCommunicationRating(): ?int
  {
    return $this->communicationRating;
  }

  /**
   * @param int|null $communicationRating
   * @return Project
   */
  public function setCommunicationRating(?int $communicationRating): Project
  {
    $this->communicationRating = $communicationRating;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getQualityOfWorkRating(): ?int
  {
    return $this->qualityOfWorkRating;
  }

  /**
   * @param int|null $qualityOfWorkRating
   * @return Project
   */
  public function setQualityOfWorkRating(?int $qualityOfWorkRating): Project
  {
    $this->qualityOfWorkRating = $qualityOfWorkRating;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getValueForMoneyRating(): ?int
  {
    return $this->valueForMoneyRating;
  }

  /**
   * @param int|null $valueForMoneyRating
   * @return Project
   */
  public function setValueForMoneyRating(?int $valueForMoneyRating): Project
  {
    $this->valueForMoneyRating = $valueForMoneyRating;
    return $this;
  }
  // Getters and Setters end
}
