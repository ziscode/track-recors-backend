<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/{apirouting}', name: 'default', requirements:["apirouting"=>"^(?!api|logout).+"], defaults:["apirouting" => null] )]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/api/test', name: 'test')]
    public function test(): JsonResponse 
    {
        return new JsonResponse(['success'=>true]);
    }
}
