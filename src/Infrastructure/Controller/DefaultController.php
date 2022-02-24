<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

class DefaultController extends AbstractController
{
    #[Route('/{apirouting}', name: 'default', requirements:["apirouting"=>"^(?!api|logout|login|reset-password).+"], defaults:["apirouting" => null] )]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/logout/test', name: 'test')]
    public function test(Request $request, MailerInterface $mailer): Response
    {

        // $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        // $mailer2 = new Mailer($transport);
        // $email = (new Email())
        //     ->from($_ENV['from_mailer'])
        //     ->to('tiagozis@gmail.com')
        //     ->subject('Time for Symfony Mailer!')
        //     ->text('Sending emails is fun again!')
        //     ->html('<p>See Twig integration for better HTML integration!</p>');

        // $mailer2->send($email);

        return new JsonResponse(['error'=>1]);
    }

}
