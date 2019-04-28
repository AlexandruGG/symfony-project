<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController
{
    private $authenticationUtils;
    private $twig;
    private $router;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        Environment $twig,
        RouterInterface $router
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->twig = $twig;
        $this->router = $router;

    }

    /**
     * @Route("/login", name="app_login")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(): Response
    {
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
}