<?php

// namespace App\Security;

// use App\Entity\User; // your user entity
// use Doctrine\ORM\EntityManagerInterface;
// use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
// use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\RouterInterface;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Exception\AuthenticationException;
// use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
// use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
// use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
// use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

// class MyGoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
// {
//     private $clientRegistry;
//     private $entityManager;
//     private $router;

//     public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
//     {
//         $this->clientRegistry = $clientRegistry;
//         $this->entityManager = $entityManager;
//         $this->router = $router;
//     }

//     public function supports(Request $request): ?bool
//     {
//         // continue ONLY if the current ROUTE matches the check ROUTE
//         return $request->attributes->get('_route') === 'connect_google_check';
//     }

//     public function authenticate(Request $request): Passport
//     {
//         $client = $this->clientRegistry->getClient('google');
//         $accessToken = $this->fetchAccessToken($client);

//         return new SelfValidatingPassport(
//             new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
//                 /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
//                 $googleUser = $client->fetchUserFromToken($accessToken);

//                 $email = $googleUser->getEmail();

//                 // Check if user already exists by email
//                 $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

//                 if ($existingUser) {
//                     return $existingUser;
//                 }

//                 // Create a new user if not exists
//                 $user = new User();
//                 $user->setEmail($email);
//                 // Add other necessary properties
//                 $this->entityManager->persist($user);
//                 $this->entityManager->flush();

//                 return $user;
//             })
//         );
//     }

//     public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//     {
//         // Redirect to homepage after successful authentication
//         return new RedirectResponse($this->router->generate('app_homepage'));
//     }

//     public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
//     {
//         // Handle authentication failure
//         return new Response('Authentication failed.', Response::HTTP_FORBIDDEN);
//     }

//     /**
//      * Called when authentication is needed, but it's not sent.
//      * This redirects to the Google OAuth authorization page.
//      */
//     public function start(Request $request, AuthenticationException $authException = null): Response
//     {
//         return new RedirectResponse(
//             '/connect/google', // Redirect to Google OAuth authorization page
//             Response::HTTP_TEMPORARY_REDIRECT
//         );
//     }
// }
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient; // Import the GoogleClient class
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['googleId' => $googleUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            // You may need to handle other user properties based on Google data
        }

        $user->setGoogleId($googleUser->getId());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function getGoogleClient(): GoogleClient
    {
        return $this->clientRegistry->getClient('google');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate('user_profile');

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/connect/google', Response::HTTP_TEMPORARY_REDIRECT);
    }
}
