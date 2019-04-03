<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AddAgentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AddAgentController extends AbstractController
{
    /**
     * @Route("/agents/new", name="add_agent")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $agent = new Agent();

        $form = $this->createForm(AddAgentType::class, $agent);
        $form->get('active')->setData(true);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            $this->addFlash("success", "Agent Submitted!");

            return $this->redirectToRoute('add_agent');
        }


        return $this->render(
            'add_agent/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

}
