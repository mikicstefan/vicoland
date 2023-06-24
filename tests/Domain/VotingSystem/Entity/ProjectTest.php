<?php

declare(strict_types=1);

namespace App\Tests\Domain\VotingSystem\Entity;

use App\Domain\VotingSystem\Entity\Client;
use App\Domain\VotingSystem\Entity\Project;
use App\Domain\VotingSystem\Entity\Vico;
use App\Domain\VotingSystem\Fixtures\ClientFixture;
use App\Domain\VotingSystem\Fixtures\VicoFixture;
use App\Tests\BaseApiWebTestCase;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Exception;

/**
 * class ProjectTest
 * Tests for Project model
 */
class ProjectTest extends BaseApiWebTestCase
{
  public const TEST_PROJECT_TITLE = 'Title ';
  public const TEST_PROJECT_RATING = 3;
  public const TEST_PROJECT_REVIEW = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
    Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
    It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially 
    unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';

  /**
   * Testing if project can be created
   *
   * @return void
   * @throws Exception
   */
  public function testingProjectCreation()
  {
    $this->addFixture(new ClientFixture());
    $this->addFixture(new VicoFixture());
    $this->executeFixtures();

    /** @var Client $client */
    $client = $this->getFixtureByReference(ClientFixture::TEST_CLIENT_REFERENCE);
    /** @var Vico $vico */
    $vico = $this->getFixtureByReference(VicoFixture::TEST_VICO_REFERENCE);

    $project = new Project();
    $project->setTitle(self::TEST_PROJECT_TITLE);
    $project->setCreator($client);
    $project->setVico($vico);
    $project->setRating(self::TEST_PROJECT_RATING);
    $project->setReview(self::TEST_PROJECT_REVIEW);
    $project->setCommunicationRating(self::TEST_PROJECT_RATING);
    $project->setQualityOfWorkRating(self::TEST_PROJECT_RATING);
    $project->setValueForMoneyRating(self::TEST_PROJECT_RATING);

    $this->entityManager->persist($project);
    $this->entityManager->flush();

    $this->assertEquals(self::TEST_PROJECT_TITLE, $project->getTitle());
    $this->assertEquals($client->getId(), $project->getCreator()->getId());
    $this->assertEquals($vico->getId(), $project->getVico()->getId());
    $this->assertEquals(self::TEST_PROJECT_RATING, $project->getRating());
    $this->assertEquals(self::TEST_PROJECT_REVIEW, $project->getReview());
    $this->assertEquals(self::TEST_PROJECT_RATING, $project->getCommunicationRating());
    $this->assertEquals(self::TEST_PROJECT_RATING, $project->getQualityOfWorkRating());
    $this->assertEquals(self::TEST_PROJECT_RATING, $project->getValueForMoneyRating());
  }

  /**
   * Testing if project can be created without vico
   *
   * @return void
   * @throws Exception
   */
  public function testingProjectWithoutVico()
  {
    $this->addFixture(new ClientFixture());
    $this->executeFixtures();

    /** @var Client $client */
    $client = $this->getFixtureByReference(ClientFixture::TEST_CLIENT_REFERENCE);

    $project = new Project();
    $project->setTitle(self::TEST_PROJECT_TITLE);
    $project->setCreator($client);
    $project->setRating(self::TEST_PROJECT_RATING);
    $project->setReview(self::TEST_PROJECT_REVIEW);
    $project->setCommunicationRating(self::TEST_PROJECT_RATING);
    $project->setQualityOfWorkRating(self::TEST_PROJECT_RATING);
    $project->setValueForMoneyRating(self::TEST_PROJECT_RATING);

    $this->entityManager->persist($project);
    $this->entityManager->flush();

    $this->assertNull($project->getVico());
  }

  /**
   * Testing if project can be created without creator
   *
   * @return void
   * @throws Exception
   */
  public function testingProjectCreatorNotNull()
  {
    $project = new Project();
    $project->setTitle(self::TEST_PROJECT_TITLE);
    $project->setRating(self::TEST_PROJECT_RATING);
    $project->setReview(self::TEST_PROJECT_REVIEW);
    $project->setCommunicationRating(self::TEST_PROJECT_RATING);
    $project->setQualityOfWorkRating(self::TEST_PROJECT_RATING);
    $project->setValueForMoneyRating(self::TEST_PROJECT_RATING);

    try {
      $this->entityManager->persist($project);
      $this->entityManager->flush();
    } catch (NotNullConstraintViolationException $e) {
      $this->assertEquals(1048, $e->getCode());
      $this->assertStringContainsString('Column \'creator_id\' cannot be null', $e->getMessage());
      return;
    }
  }

  /**
   * Testing if project can be created without title
   *
   * @return void
   * @throws Exception
   */
  public function testingProjectTitleNotNull()
  {
    $this->addFixture(new ClientFixture());
    $this->executeFixtures();

    /** @var Client $client */
    $client = $this->getFixtureByReference(ClientFixture::TEST_CLIENT_REFERENCE);

    $project = new Project();
    $project->setCreator($client);

    try {
      $this->entityManager->persist($project);
      $this->entityManager->flush();
    } catch (NotNullConstraintViolationException $e) {
      $this->assertEquals(1048, $e->getCode());
      $this->assertStringContainsString('Column \'title\' cannot be null', $e->getMessage());
      return;
    }
  }

  /**
   * Testing if project rating, review and other ratings are nullable
   *
   * @return void
   */
  public function testingProjectRatingReviewAndOtherRatingsNullable()
  {
    $this->addFixture(new ClientFixture());
    $this->executeFixtures();

    /** @var Client $client */
    $client = $this->getFixtureByReference(ClientFixture::TEST_CLIENT_REFERENCE);

    $project = new Project();
    $project->setTitle(self::TEST_PROJECT_TITLE);
    $project->setCreator($client);

    $this->entityManager->persist($project);
    $this->entityManager->flush();

    $this->assertNull($project->getRating());
    $this->assertNull($project->getReview());
    $this->assertNull($project->getCommunicationRating());
    $this->assertNull($project->getQualityOfWorkRating());
    $this->assertNull($project->getValueForMoneyRating());
  }
}
