<?php

namespace App\Core\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class UserProvider
 */
class UserProvider implements UserProviderInterface
{
  /**
   * Define new User for authentication's SelfValidatingPassport
   *
   * @param mixed $identifier
   * @return UserInterface
   */
  public function loadUserByIdentifier(mixed $identifier): UserInterface
  {
    return new User();
  }

  /**
   * Refreshes the user.
   *
   * It is up to the implementation to decide if the user data should be
   * totally reloaded
   *
   * @param UserInterface $user
   * @return User
   */
  public function refreshUser(UserInterface $user): User
  {
    if (!$user instanceof User) {
      throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
    }

    return new User();
  }

  /**
   * Whether this provider supports the given user class.
   *
   * @param string $class
   * @return bool
   */
  public function supportsClass(string $class): bool
  {
    return User::class === $class;
  }
}
