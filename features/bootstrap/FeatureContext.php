<?php

namespace FeatureContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;

class FeatureContext implements Context
{

    public function __construct(EntityManagerInterface $entityManager)
    {
    }
}
