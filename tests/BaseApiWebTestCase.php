<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseApiWebTestCase
 */
abstract class BaseApiWebTestCase extends WebTestCase
{
  /** @var KernelBrowser|null $client */
  protected ?KernelBrowser $client;

  /** @var EntityManagerInterface $entityManager */
  protected EntityManagerInterface $entityManager;

  /**
   * @var ORMExecutor|null
   */
  private ?ORMExecutor $fixtureExecutor;

  /**
   * @var ContainerAwareLoader|null
   */
  private ?ContainerAwareLoader $fixtureLoader;

  /**
   * @var string
   */
  protected string $authToken;

  public function setUp(): void
  {
    // This calls KernelTestCase::bootKernel(), and creates a
    // "client" that is acting as the browser
    $this->client = static::createClient();
    $this->entityManager = self::getContainer()->get('doctrine')->getManager();
    $this->getFixtureLoader();
    $this->getFixtureExecutor();

    // get auth token for tests
    $this->authToken = strval($this->client->getContainer()->getParameter('voting.system.authToken'));
  }

  /**
   * Add a new fixture to be loaded
   * @param FixtureInterface $fixture
   * @return void
   */
  protected function addFixture(FixtureInterface $fixture): void
  {
    $this->fixtureLoader->addFixture($fixture);
  }

  /**
   * Executes all the fixtures that have been loaded so far
   * @return void
   */
  protected function executeFixtures(): void
  {
    $this->fixtureExecutor->execute($this->fixtureLoader->getFixtures());
  }

  /**
   * @return void
   */
  private function getFixtureExecutor(): void
  {
    $this->fixtureExecutor = new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager));
  }

  /**
   * @return void
   */
  private function getFixtureLoader(): void
  {
    $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
  }

  public function tearDown(): void
  {
    parent::tearDown();
    $this->client = null;
    $this->entityManager->clear();
    $this->fixtureExecutor = null;
    $this->fixtureLoader=null;
  }

  // region Custom Functions

  /**
   * @param string $fixtureReferenceName
   * @return object
   */
  protected function getFixtureByReference(string $fixtureReferenceName): object
  {
    return $this->fixtureExecutor->getReferenceRepository()->getReference($fixtureReferenceName);
  }
  // endregion Custom Functions

  /**
   * @param string $method
   * @param string $path
   * @return void
   */
  protected function testWithoutAuthorizationBase(string $method, string $path): void
  {
    $this->client->request(
      $method,
      $path
    );

    $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
  }

  /**
   * @param string $method
   * @param string $path
   * @return void
   */
  protected function testWithInvalidAuthTokenBase(string $method, string $path): void
  {
    $this->client->request(
      $method,
      $path,
      [],
      [],
      [
        'HTTP_authorization' => 'invalid-auth-token'
      ]
    );

    $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
  }

  /**
   * @param string $method
   * @param string $path
   * @return void
   */
  protected function testWithoutAuthTokenBase(string $method, string $path): void
  {
    $this->client->request(
      $method,
      $path,
      [],
      [],
      [
        'HTTP_authorization' => [],
      ]
    );

    $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
  }
}
