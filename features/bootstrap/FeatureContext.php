<?php

namespace FeatureContext;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ResponseTextException;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManagerInterface;

class FeatureContext extends RawMinkContext implements Context
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Then an agent should exist on the :arg1 company page with name :arg2
     * @param $companyName
     * @param $agentName
     * @throws ResponseTextException
     */
    public function anAgentShouldExistOnTheCompanyPageWithName(string $companyName, string $agentName)
    {
        $company = $this->getCompanyRepository()->findOneByName(['name' => $companyName]);

        $this->visitPath(sprintf('/company/%d/agents', $company->getId()));
        $this->assertSession()->pageTextContains($agentName);
    }

    private function getCompanyRepository(): CompanyRepository
    {
        return $this->entityManager->getRepository(Company::class);
    }
}
