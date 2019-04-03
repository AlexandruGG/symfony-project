<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddAgentControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetAddAgentPage()
    {
        $client = static::createClient();
        $client->request('GET', '/agents/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddNewAgent()
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/agents/new');
        $form = $crawler->selectButton('add_agent_save')->form();

        $formData = ['Functional Name', true, 5];

        $client->submit(
            $form,
            [
                'add_agent[name]' => $formData[0],
                'add_agent[active]' => $formData[1],
                'add_agent[company]' => $formData[2],
            ]
        );

        $agentRepo = $this->entityManager->getRepository(\App\Entity\Agent::class)->findOneBy(
            [
                'name' => $formData[0],
                'active' => $formData[1],
                'company' => $formData[2],
            ]
        );

        $this->assertNotNull($agentRepo);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

}