<?php

namespace App\Domain\VotingSystem\Fixtures;

use App\Domain\VotingSystem\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ClientFixture
 */
class ClientFixture extends Fixture
{
  public const TEST_CLIENT_REFERENCE = 'client';
  public const TEST_CLIENT_USERNAME = 'Test Username ';
  public const TEST_CLIENT_PASSWORD= 'test123';
  public const TEST_CLIENT_FIRST_NAME = 'Stefan';
  public const TEST_CLIENT_LAST_NAME = 'Mikic';

  /**
   * @param ObjectManager $manager
   * @return void
   */
  public function load(ObjectManager $manager): void
  {
    $client = new Client();
    $client->setUsername(uniqid(self::TEST_CLIENT_USERNAME));
    $client->setPassword(password_hash(self::TEST_CLIENT_PASSWORD, PASSWORD_BCRYPT));
    $client->setFirstName(self::TEST_CLIENT_FIRST_NAME);
    $client->setLastName(self::TEST_CLIENT_LAST_NAME);

    $manager->persist($client);
    $manager->flush();

    $this->addReference(self::TEST_CLIENT_REFERENCE, $client);
  }
}
