<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Response;
use Twig\Environment;


class CompanyAgentsController
{

    private $entityManager;
    private $twig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    /**
     * @Route("/company/{companyId}/agents", name="company_agents", requirements={"companyId"="\d+"})
     * @param int $companyId
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(int $companyId)
    {
        $company = $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['id' => $companyId]);

        if (!$company) {
            throw new NotFoundHttpException(sprintf("Company %s Doesn't Exist", $companyId));
        }

        $agents = $this->entityManager
            ->getRepository(Agent::class)
            ->findBy(['company' => $company->getId()]);

        $content = $this->twig->render(
            'company_agents/index.html.twig',
            [
                'company' => $company,
                'agents' => $agents,
            ]
        );

        return new Response($content);
    }
}
