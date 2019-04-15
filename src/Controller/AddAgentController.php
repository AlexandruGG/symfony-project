<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AddAgentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;


class AddAgentController
{
    private $entityManager;
    private $formFactory;
    private $twig;
    private $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        Environment $twig,
        RouterInterface $router

    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @Route("admin/agents/new", name="add_agent")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request)
    {
        $agent = new Agent();

        $form = $this->formFactory->create(AddAgentType::class, $agent);
        $form->get('active')->setData(true);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent = $form->getData();

            $this->entityManager->persist($agent);
            $this->entityManager->flush();

            $request->getSession()->getFlashBag()->add("success", "Agent Submitted!");

            return new RedirectResponse($this->router->generate('add_agent'));
        }

        $content = $this->twig->render(
            'add_agent/index.html.twig',
            [
                'addAgentForm' => $form->createView(),
            ]
        );

        return new Response($content);
    }

}
