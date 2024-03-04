<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User; // Import the User entity

class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser(); // user from token 

        if ($user instanceof User && $user->isVerified()) { 
            $roles = $token->getRoleNames();
            if (in_array('ROLE_ADMIN', $roles, true)) {
                return new RedirectResponse($this->urlGenerator->generate('app_admin'));
            } elseif (in_array('ROLE_USER', $roles, true)) {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            }
        }

        // If user is not verified or doesn't have appropriate roles, redirect to confirmaccount.html.twig
        return new RedirectResponse($this->urlGenerator->generate('confirm_account'));
    }
}
