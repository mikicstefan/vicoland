<?php

declare(strict_types=1);

namespace App\Domain\VotingSystem\Representation;

use App\Domain\VotingSystem\Entity\Client;
use DateTime;

/**
 * Class ClientRepresentation
 */
class ClientRepresentation
{
  /**
   * @param int $id
   * @param string $username
   * @param string $firstName
   * @param string $lastName
   * @param DateTime $created
   */
  private function __construct(
    private int $id,
    private string $username,
    private string $firstName,
    private string $lastName,
    private DateTime $created
  ) {
  }

  /**
   * @param Client $client
   * @return self
   */
  public static function fromEntity(Client $client): self
  {
    return new self(
      $client->getId(),
      $client->getUsername(),
      $client->getFirstName(),
      $client->getLastName(),
      $client->getCreated(),
    );
  }
}
