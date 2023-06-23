<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Helpers;

use Doctrine\ORM\QueryBuilder;

/**
 * Class CriteriaProcessor.
 */
class CriteriaProcessor
{
  /**
   * @param QueryBuilder $queryBuilder
   * @param array<string> $criteria
   * @return QueryBuilder
   */
  public static function processCriteria(QueryBuilder $queryBuilder, array $criteria = []): QueryBuilder
  {
    foreach ($criteria as $key => $criterion) {
      $keyDotless = str_replace('.', '', $key);

      $queryBuilder->andWhere("$key LIKE :$keyDotless")
        ->setParameter($keyDotless, '%' . $criterion . '%');
    }

    return $queryBuilder;
  }
}
