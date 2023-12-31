<?php

namespace App\Domain\VotingSystem\Repository;

use App\Core\Doctrine\Repository\BaseRepository;
use App\Domain\VotingSystem\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends BaseRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Client::class);
  }
}
