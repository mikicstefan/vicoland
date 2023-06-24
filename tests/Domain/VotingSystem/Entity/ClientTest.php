<?php

declare(strict_types=1);

namespace App\Tests\Domain\VotingSystem\Entity;

use App\Domain\VotingSystem\Entity\Client;
use App\Tests\BaseApiWebTestCase;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * class ClientTest
 * Tests for Client model
 */
class ClientTest extends BaseApiWebTestCase
{
  public const TEST_CLIENT_USERNAME = 'Test Username ';
  public const TEST_CLIENT_PASSWORD= 'test123';
  public const TEST_CLIENT_FIRST_NAME = 'Stefan';
  public const TEST_CLIENT_LAST_NAME = 'Mikic';

  /**
   * Testing if client can be created
   *
   * @return void
   */
  public function testingClientCreation()
  {
    $client = new Client();
    $client->setUsername(self::TEST_CLIENT_USERNAME);
    $client->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client->setLastName(self::TEST_CLIENT_LAST_NAME);

    $this->entityManager->persist($client);
    $this->entityManager->flush();

    $this->assertEquals(self::TEST_CLIENT_USERNAME, $client->getUsername());
    $this->assertEquals(self::TEST_CLIENT_FIRST_NAME, $client->getFirstName());
    $this->assertEquals(self::TEST_CLIENT_LAST_NAME, $client->getLastName());
  }

  /**
   * Testing if client can be created without username
   *
   * @return void
   */
  public function testingClientUsernameNotNull()
  {
    $client = new Client();
    $client->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client->setLastName(self::TEST_CLIENT_LAST_NAME);

    try {
      $this->entityManager->persist($client);
      $this->entityManager->flush();
    } catch (NotNullConstraintViolationException $e) {
      $this->assertEquals(1048, $e->getCode());
      $this->assertStringContainsString('Column \'username\' cannot be null', $e->getMessage());
      return;
    }
  }

  /**
   * Testing if 2 clients can have equal username
   *
   * @return void
   */
  public function testingClientUsernameUniqueness()
  {
    $client1 = new Client();
    $client1->setUsername(self::TEST_CLIENT_USERNAME);
    $client1->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client1->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client1->setLastName(self::TEST_CLIENT_LAST_NAME);

    $client2 = new Client();
    $client2->setUsername(self::TEST_CLIENT_USERNAME);
    $client2->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client2->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client2->setLastName(self::TEST_CLIENT_LAST_NAME);

    try {

      $this->entityManager->persist($client1);
      $this->entityManager->persist($client2);
      $this->entityManager->flush();
    } catch (UniqueConstraintViolationException $e) {
      $this->assertEquals(1062, $e->getCode());
      $this->assertStringContainsString('Duplicate entry', $e->getMessage());
      return;
    }
  }

  /**
   * Testing if 2 clients with different usernames can be created
   *
   * @return void
   */
  public function testingClientUsernameNonUniqueness()
  {
    $client1 = new Client();
    $client1->setUsername(uniqid(self::TEST_CLIENT_USERNAME));
    $client1->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client1->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client1->setLastName(self::TEST_CLIENT_LAST_NAME);

    $client2 = new Client();
    $client2->setUsername(uniqid(self::TEST_CLIENT_USERNAME));
    $client2->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client2->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client2->setLastName(self::TEST_CLIENT_LAST_NAME);

    $this->entityManager->persist($client1);
    $this->entityManager->persist($client2);
    $this->entityManager->flush();

    $this->assertNotEquals($client1->getId(), $client2->getId());
  }
}
