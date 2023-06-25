<?php

namespace App\VotingSystem\Handler;

use App\Core\REST\Exception\NotFoundEntityException;
use App\Domain\Translation\ValueObject\ErrorMessage;
use App\Domain\VotingSystem\Entity\Project;
use App\Domain\VotingSystem\Repository\ProjectRepository;
use App\Domain\VotingSystem\Representation\ClientRepresentation;
use App\Domain\VotingSystem\Representation\ProjectRepresentation;
use App\Domain\VotingSystem\Representation\VicoRepresentation;
use App\VotingSystem\Message\RateProjectMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class RateProjectHandler
 */
#[AsMessageHandler]
class RateProjectHandler
{
  /**
   * @param EntityManagerInterface $entityManager
   * @param ProjectRepository $projectRepository
   */
  public function __construct(
    private EntityManagerInterface $entityManager,
    private ProjectRepository $projectRepository
  ) {
  }


  /**
   * @param RateProjectMessage $message
   * @return ProjectRepresentation
   * @throws NotFoundEntityException
   * @SuppressWarnings(PHPMD)
   */
  public function __invoke(RateProjectMessage $message): ProjectRepresentation
  {
    $project = $this->projectRepository->findOneBy(['id' => $message->projectId()]);

    if (!$project instanceof Project) {
      throw new NotFoundEntityException(sprintf(ErrorMessage::ERR_ENTITY_NOT_FOUND, 'Project'));
    }

    $project->setRating($message->rating());
    $project->setReview($message->review());

    if ($message->communicationRating()) {
      $project->setCommunicationRating($message->communicationRating());
    }

    if ($message->qualityOfWorkRating()) {
      $project->setQualityOfWorkRating($message->qualityOfWorkRating());
    }

    if ($message->valueForMoneyRating()) {
      $project->setValueForMoneyRating($message->valueForMoneyRating());
    }

    $this->entityManager->flush();

    $projectRepresentation = ProjectRepresentation::fromEntity($project)
      ->withCreator(ClientRepresentation::fromEntity($project->getCreator()));

    if (!empty($project->getVico())) {
      $projectRepresentation->withVico(VicoRepresentation::fromEntity($project->getVico()));
    }

    return $projectRepresentation;
  }
}
