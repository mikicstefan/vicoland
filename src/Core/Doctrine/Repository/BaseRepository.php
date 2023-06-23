<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Doctrine\Helpers\CriteriaProcessor;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository extends ServiceEntityRepository
{
  /**
   * Returns last added item form the DB.
   */
  public function getLast(): mixed
  {
    return $this->findOneBy([], ['id' => 'DESC']);
  }

  /**
   * Return all results or from selected range if parameters are given.
   *
   * @param string $alias
   * @param array<string> $criteria
   * @param array<string> $orderBy
   * @param int|null $limit
   * @param int|null $offset
   * @param array<string> $fetch
   * @return mixed
   */
  public function findByWithFetch(
    string $alias,
    array $criteria = [],
    array $orderBy = [],
    int|null $limit = null,
    int|null $offset = null,
    array $fetch = []
  ): mixed {
    $queryBuilder = $this->buildFetchQuery($alias, $criteria, $orderBy, $limit, $offset, $fetch);

    return $queryBuilder->getQuery()->getResult();
  }

  /**
   * Build query builder and also select records from given range if parameters are given.
   *
   * @param string $alias
   * @param array<string> $criteria
   * @param array<string> $orderBy
   * @param int|null $limit
   * @param int|null $offset
   * @param array<string> $fetch
   * @SuppressWarnings(PHPMD)
   * @return QueryBuilder
   */
  protected function buildFetchQuery(
    string $alias,
    array $criteria = [],
    array $orderBy = [],
    int|null $limit = null,
    int|null $offset = null,
    array $fetch = []
  ): QueryBuilder {
    $queryBuilder = $this->createQueryBuilder($alias);

    if ($limit) {
      $queryBuilder->setMaxResults($limit);
    }

    if ($offset) {
      $queryBuilder->setFirstResult($offset);
    }

    foreach ($fetch as $key => $table) {
      $queryBuilder->leftJoin($table, $key);
    }

    foreach ($orderBy as $sort => $order) {
      $queryBuilder->addOrderBy($sort, $order);
    }

    return CriteriaProcessor::processCriteria($queryBuilder, $criteria);
  }

  /**
   * Count all records for given alias.
   *
   * @param string $alias
   * @param array<string> $criteria
   * @param array<string> $orderBy
   * @param array<string> $fetch
   * @return int
   */
  public function countByWithFetch(
    string $alias,
    array $criteria = [],
    array $orderBy = [],
    array $fetch = []
  ): int {
    $queryBuilder = $this->buildFetchQuery($alias, $criteria, $orderBy, null, null, $fetch);

    return count((array)$queryBuilder->getQuery()->getResult());
  }
}
