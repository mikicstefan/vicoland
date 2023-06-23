<?php

namespace App\Domain\VotingSystem\Repository;

use App\Core\Doctrine\Repository\BaseRepository;
use App\Domain\VotingSystem\Entity\Vico;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vico[]    findAll()
 * @method Vico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VicoRepository extends BaseRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Vico::class);
  }
}
