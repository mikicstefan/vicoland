<?php


declare(strict_types=1);

namespace App\Domain\VotingSystem\Representation;

use App\Domain\VotingSystem\Entity\Client;
use App\Domain\VotingSystem\Entity\Project;
use App\Domain\VotingSystem\Entity\Vico;
use DateTime;

/**
 * Class ProjectRepresentation
 */
class ProjectRepresentation
{
  /**
   * @var ClientRepresentation
   */
  private ClientRepresentation $clientRepresentation;

  /**
   * @var VicoRepresentation
   */
  private VicoRepresentation $vicoRepresentation;

    /**
     * @param int $id
     * @param string $title
     * @param int|null $rating
     * @param null $review
     * @param int|null $communicationRating
     * @param int|null $qualityOfWorkRating
     * @param int|null $valueForMoneyRating
     * @param DateTime $created
     * @param DateTime|null $updated
     */
  private function __construct(
    private int      $id,
    private string   $title,
    private ?int $rating,
    private $review = null,
    private ?int $communicationRating,
    private ?int $qualityOfWorkRating,
    private ?int $valueForMoneyRating,
    private DateTime $created,
    private ?DateTime $updated
  )
  {
  }

  /**
   * @param Project $project
   * @return self
   */
  public static function fromEntity(Project $project): self
  {
    return new self(
      $project->getId(),
      $project->getTitle(),
      $project->getRating(),
      $project->getReview(),
      $project->getCommunicationRating(),
      $project->getQualityOfWorkRating(),
      $project->getValueForMoneyRating(),
      $project->getCreated(),
      $project->getUpdated()
    );
  }

  // region with representation
  /**
   * @param ClientRepresentation $representation
   * @return $this
   */
  public function withCreator(ClientRepresentation $representation): self
  {
    $this->clientRepresentation = $representation;

    return $this;
  }

  /**
   * @param VicoRepresentation $representation
   * @return $this
   */
  public function withVico(VicoRepresentation $representation): self
  {
    $this->vicoRepresentation = $representation;

    return $this;
  }
  // endregion with representation
}
