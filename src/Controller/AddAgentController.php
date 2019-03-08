<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddAgentController extends AbstractController
{
    /**
     * @Route("/agents/new", name="add_agent")
     */
    public function index()
    {
        return $this->render('add_agent/index.html.twig', [
            'controller_name' => 'AddAgentController',
        ]);
    }
}
