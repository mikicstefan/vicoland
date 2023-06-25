<?php

namespace App\Domain\VotingSystem\Fixtures;

use App\Domain\VotingSystem\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ProjectWithVicoFixture
 */
class ProjectWithVicoFixture extends Fixture implements DependentFixtureInterface
{
  public const TEST_PROJECT_WITH_VICO_REFERENCE = 'project-with-vico';
  public const TEST_PROJECT_TITLE = 'Title ';

  /**
   * @param ObjectManager $manager
   * @return void
   */
  public function load(ObjectManager $manager): void
  {
    $client = $this->getReference(ClientFixture::TEST_CLIENT_REFERENCE);
    $vico = $this->getReference(VicoFixture::TEST_VICO_REFERENCE);

    $project = new Project();
    $project->setTitle(self::TEST_PROJECT_TITLE);
    $project->setCreator($client);
    $project->setVico($vico);

    $manager->persist($project);
    $manager->flush();

    $this->addReference(self::TEST_PROJECT_WITH_VICO_REFERENCE, $project);
  }

  public function getDependencies()
  {
    return [
      ClientFixture::class,
      VicoFixture::class
    ];
  }
}
