<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Form\AddAgentType;
use App\Util\RegistrationUtil;
use App\Util\RegistrationUtilUtil;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


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
     * @Route("admin/agent/new", name="add_agent")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Swift_Mailer $mailer)
    {
        $agent = new Agent();

        $form = $this->formFactory->create(AddAgentType::class, $agent);
        $form->get('active')->setData(true);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Agent $agent */
            $agent = $form->getData();
            $email = $form->get('email')->getData();

            $user = new User();
            $user->setEmail($email);
            $user->setPassword('temporary');
            $user->setRoles(['ROLE_AGENT']);

            $registrationKey = RegistrationUtil::generateRegistrationKey();
            $user->setRegistrationKey($registrationKey);

            $agent->addUser($user);

            $this->entityManager->persist($agent);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $agentName = $agent->getName();
            $this->sendRegistrationEmail($mailer, $agentName, $registrationKey);

            $request->getSession()->getFlashBag()->add('success', 'Agent Submitted!');

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

    private function sendRegistrationEmail(Swift_Mailer $mailer, string $agentName, string $registrationKey)
    {
        $url = $this->router->generate(
            'app_register',
            ['registrationKey' => $registrationKey],
            RouterInterface::ABSOLUTE_URL
        );
        $message = (new Swift_Message(('Hello Agent')))->setFrom(trim('admin@goodlord.co'))->setTo(
            trim($agentName.'@goodlord.co')
        )
            ->setBody(
                "<a href='$url'>Click to register your account</a>",
                'text/html'
            );

        $mailer->send($message);
    }

}
