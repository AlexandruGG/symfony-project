<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CreateCompanyType;
use App\Util\PaymentUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateCompanyController
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
     * @Route("admin/company/create", name="create_company")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request)
    {
        $company = new Company();

        $form = $this->formFactory->create(CreateCompanyType::class, $company);
        $form->get('paymentReference')->setData(PaymentUtil::generatePaymentReference());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();

            $this->entityManager->persist($company);
            $this->entityManager->flush();

            $request->getSession()->getFlashBag()->add('success', 'Company Created!');

            return new RedirectResponse($this->router->generate('create_company'));
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
