<?php

declare(strict_types=1);

namespace App\Tests\Domain\VotingSystem\Entity;

use App\Domain\VotingSystem\Entity\Vico;
use App\Tests\BaseApiWebTestCase;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

/**
 * class VicoTest
 * Tests for Vico model
 */
class VicoTest extends BaseApiWebTestCase
{
  public const TEST_VICO_NAME = 'Team ';

  /**
   * Testing if client can be created
   *
   * @return void
   */
  public function testingVicoCreation()
  {
    $vico = new Vico();
    $vico->setName(self::TEST_VICO_NAME);

    $this->entityManager->persist($vico);
    $this->entityManager->flush();

    $this->assertEquals(self::TEST_VICO_NAME, $vico->getName());
  }

  /**
   * Testing if client can be created without username
   *
   * @return void
   */
  public function testingVicoNameNotNull()
  {
    $vico = new Vico();

    try {
      $this->entityManager->persist($vico);
      $this->entityManager->flush();
    } catch (NotNullConstraintViolationException $e) {
      $this->assertEquals(1048, $e->getCode());
      $this->assertStringContainsString('Column \'name\' cannot be null', $e->getMessage());
      return;
    }
  }
}
