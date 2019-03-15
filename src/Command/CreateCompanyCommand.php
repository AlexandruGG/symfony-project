<?php

namespace App\Command;


use App\Entity\Company;
use App\Util\PaymentUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCompanyCommand extends Command
{
    protected static $defaultName = 'app:create-company';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new company')
            ->setHelp('This command creates a new company')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of company');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $company = (new Company())->setName($input->getArgument('name'))->setPaymentReference(
                PaymentUtil::generatePaymentReference()
            );
        } catch (\Exception $e) {
            $company = null;
        }
        $this->entityManager->persist($company);
        $this->entityManager->flush();
    }

}