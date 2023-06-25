<?php

namespace App\Domain\VotingSystem\Repository;

use App\Core\Doctrine\Repository\BaseRepository;
use App\Domain\VotingSystem\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends BaseRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Project::class);
  }
}
