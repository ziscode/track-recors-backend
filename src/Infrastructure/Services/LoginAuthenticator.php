<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Doctrine\ORM\EntityManagerInterface;
use App\DataBase\Entity\User;

class LoginAuthenticator extends AbstractAuthenticator
{
    public const LOGIN_ROUTE = 'api_login';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        
        if (!empty($request->getContent())) {
            $json = json_decode($request->getContent(), true);
            $password = $json && isset($json['password']) ? $json['password'] : null;
            $username = $json && isset($json['username']) ? $json['username'] : null;
            $csrfToken = $json && isset($json['csrf_token']) ? $json['csrf_token'] : null;
            $rememberMe = $json && isset($json['remember_me']) ? $json['remember_me'] : null;
        } else {
            $password = $request->request->get('password');
            $username = $request->request->get('username');
            $csrfToken = $request->request->get('csrf_token');
            $rememberMe = $request->request->get('remember_me');
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $username]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        } else if (!$user->getEnabled()) {
            throw new CustomUserMessageAuthenticationException('Your user account is disabled');
        }
        
        $params = [];

        if ($rememberMe === true) {
            $params[] = new RememberMeBadge();
        }

        if (!empty($csrfToken)) {
            $params[] = new CsrfTokenBadge('login', $csrfToken);
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            $params
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data);
    }
}