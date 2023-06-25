<?php

namespace App\Domain\VotingSystem\Fixtures;

use App\Domain\VotingSystem\Entity\Vico;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class VicoFixture
 */
class VicoFixture extends Fixture
{
  public const TEST_VICO_REFERENCE = 'vico';
  public const TEST_VICO_NAME = 'Team ';

  /**
   * @param ObjectManager $manager
   * @return void
   */
  public function load(ObjectManager $manager): void
  {
    $vico = new Vico();
    $vico->setName(uniqid(self::TEST_VICO_NAME));

    $manager->persist($vico);
    $manager->flush();

    $this->addReference(self::TEST_VICO_REFERENCE, $vico);
  }
}
