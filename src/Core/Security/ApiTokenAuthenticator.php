<?php

namespace App\Core\Security;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * Class ApiTokenAuthenticator
 *
 * Authenticator used to authenticate Vicoland endpoints.
 * Not real authentication, comparing token with hardcoded token in ENV
 */
class ApiTokenAuthenticator extends AbstractAuthenticator
{
  /** @var string key used in HTTP header request to Authorize on server */
  private const AUTHORIZATION_HEADER_KEY = 'Authorization';

  /**
   * ApiTokenAuthenticator constructor
   *
   * @param ParameterBagInterface $params used for getting parameters from configuration files
   * @param UserProvider $userProvider used for getting user
   */
  public function __construct(private ParameterBagInterface $params, private UserProvider $userProvider)
  {
  }

  /**
   * Check if authenticator should be called
   * If no API key is defined from backend side disable auth
   *
   * @param Request $request
   * @return bool|null
   */
  public function supports(Request $request): ?bool
  {
    if (empty($request->headers->all(self::AUTHORIZATION_HEADER_KEY))) {
      throw new CustomUserMessageAuthenticationException('Authorization header is missing', code: 401);
    };

    return true;
  }

  /**
   * Based on received api token determine if someone can access the endpoint
   *
   * @param Request $request
   * @return Passport
   */
  public function authenticate(Request $request): Passport
  {
    $getAuthToken = $this->params->get('voting.system.authToken');

    $authorization = $request->headers->get(self::AUTHORIZATION_HEADER_KEY) ?? '';

    $apiToken = str_contains($authorization, 'Bearer') ? substr($authorization, strlen('Bearer ')) : $authorization;

    if (null == $apiToken) {
      // The token header was empty, authentication fails with HTTP Status
      // Code 401 "Unauthorized"
      throw new CustomUserMessageAuthenticationException('No API token provided');
    }

    if ($getAuthToken !== $apiToken) {
      throw new CustomUserMessageAuthenticationException('Given API token is not valid');
    }


    return new SelfValidatingPassport(
      new UserBadge($apiToken, function () use ($apiToken) {
        return $this->userProvider->loadUserByIdentifier($apiToken);
      })
    );
  }

  /**
   * Determine what we should do if authentication is passed.
   *
   * If you return null, the current request will continue, and the user
   * will be authenticated. This makes sense, for an API.
   *
   * @param Request $request
   * @param TokenInterface $token
   * @param string $firewallName
   * @return Response|null
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    // on success, let the request continue
    return null;
  }

  /**
   * Determine how we handle failed authentication
   *
   * @param Request $request
   * @param AuthenticationException $exception
   * @return Response|null
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
  {
    $data = [
      'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
    ];

    if ($this->params->get('app.env') === 'prod') {
      return new Response('', Response::HTTP_UNAUTHORIZED);
    }

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}
