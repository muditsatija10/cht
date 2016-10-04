<?php

namespace ITG\MillBundle\Security;

use Doctrine\ORM\EntityManager;
use ITG\MillBundle\Exception\VisibleException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class TokenAuthenticator implements SimplePreAuthenticatorInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createToken(Request $request, $providerKey)
    {
        $token = $request->headers->get('X-AUTH-TOKEN');

        if (!$token)
        {
            throw new VisibleException('No token found', 999, null, 401);
        }

        return new PreAuthenticatedToken('anon', $token, $providerKey);
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $key = $token->getCredentials();
        $user = $userProvider->getUserForToken($key);

        if (!$user)
        {
            throw new VisibleException('Bad token', 999, null, 401);
        }

        return new PreAuthenticatedToken(
            $user,
            $key,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}