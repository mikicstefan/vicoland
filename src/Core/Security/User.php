<?php

namespace App\Core\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface
{
  /**
   * Returns the roles granted to the user.
   *
   * @return array<string>
   */
  public function getRoles(): array
  {
    // TODO: Implement getRoles() method.
    return [];
  }

  /**
   * Removes sensitive data from the user.
   *
   * @return void
   */
  public function eraseCredentials()
  {
    // TODO: Implement eraseCredentials() method.
  }

  /**
   * Returns the identifier for this user (e.g. its username or email address).
   *
   * @return string
   */
  public function getUserIdentifier(): string
  {
    // TODO: Implement getUserIdentifier() method.

    return '';
  }

  /**
   * Whether this provider supports the given user class.
   *
   * @param mixed $class
   * @return bool
   */
  public function supportsClass(mixed $class): bool
  {
    return $class === self::class;
  }
}
