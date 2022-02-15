<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\Services\SerializerValidator;
use App\Infrastructure\Entity\Person;
use App\Infrastructure\Dto\TrackingRecord;

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
    public function test(Request $request, SerializerValidator $service): JsonResponse 
    {

        
        $list = $service->validateAll($request->getContent(), TrackingRecord::class);
        $erros = [];

        foreach($list as $item) {
        
            if ($item instanceof TrackingRecord) {
                //TODO save item
            } else {
                $erros[] = $item;
            }

        }

        if (!empty($erros)) {
            return new JsonResponse($erros);
        } else {
            return new JsonResponse(['success'=>true]);
        }

        


        // dump(json_decode($request->getContent()));die;
        $result = $service->validate($request->getContent(), TrackingRecord::class);

        if ($result instanceof TrackingRecord) {
            return JsonResponse::fromJsonString($service->serialize($result));
        }
        
        return new JsonResponse($result);
        
        // $result = $service->validate($request->get('data'), Person::class);

        // if ($result instanceof Person) {
        //     return JsonResponse::fromJsonString($service->serialize($result));
        // }
        
        // return new JsonResponse($result);
    }
}
