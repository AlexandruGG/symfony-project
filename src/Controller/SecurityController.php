<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController
{
    private $authenticationUtils;
    private $twig;
    private $router;
    private $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationUtils $authenticationUtils,
        Environment $twig,
        RouterInterface $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationUtils = $authenticationUtils;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @Route("/login", name="app_login")
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login()
    {
        if ($this->isUserLogged()) {
            return new RedirectResponse($this->router->generate('homepage'));
        }

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        $content = $this->twig->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );

        return new Response($content);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
    }

    private function isUserLogged()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $user instanceof User;
    }
}
