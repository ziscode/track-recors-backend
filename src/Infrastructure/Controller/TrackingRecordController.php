<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\Services\SerializerValidator;
use App\Infrastructure\Dto\TrackingRecordApp;
use App\Infrastructure\Dto\TrackingRecordWeb;
use App\Domain\UseCases\TrackingRecordUseCases;
use App\Infrastructure\Assemblers\TrackingRecordAssembler;

class TrackingRecordController extends AbstractController
{

    private $service;
    private $assembler;
    private $useCases;

    public function __construct(SerializerValidator $service, 
        TrackingRecordAssembler $assembler, TrackingRecordUseCases $useCases) 
    {
        $this->service = $service;
        $this->assembler = $assembler;
        $this->useCases = $useCases;
    }

    #[Route('/trackingrecord/record', name: 'tracking_record')]
    public function index(): Response
    {
        return $this->render('tracking_record/index.html.twig', [
            'controller_name' => 'TrackingRecordController',
        ]);
    }

    #[Route('/api/trackingrecord/save', 'tracking_record_save')]
    public function save(Request $request) : JsonResponse
    {
        $resource = $this->service->validate($request->getContent(), TrackingRecordWeb::class);

        if ($resource instanceof TrackingRecordWeb) {
            $this->useCases->create($this->assembler->resourceToEntity($resource));
            return new JsonResponse(['success'=>true]);
        }
        
        return new JsonResponse($resource);
    }
    
    #[Route('api/trackingrecord/update', 'traking_record_update')]
    public function update(Request $request): JsonResponse
    {
        $resource = $this->service->validate($request->getContent(), TrackingRecordWeb::class);

        if ($resource instanceof TrackingRecordWeb) {
            $this->useCases->update($this->assembler->resourceToEntity($resource));
            return new JsonResponse(['success'=>true]);
        }
        
        return new JsonResponse($resource);
    }

    #[Route('api/trackingrecord/find/{id}', 'traking_record_find')]
    public function find(Request $request, $id): JsonResponse
    {
        $entity = $this->useCases->retrieve($id);
        $resource = $this->service->serialize(
            $this->assembler->entityToResource($entity));

        return JsonResponse::fromJsonString($resource);
    }

    #[Route('api/trackingrecord/remove/{id}', 'traking_record_remove')]
    public function remove(Request $request, $id): JsonResponse
    {
        $entity = $this->useCases->delete($id);
        return new JsonResponse(['success'=>true]);
    }

    #[Route('api/trackingrecord/list')]
    public function list(Request $request): JsonResponse
    {
        $list = $this->useCases->list();
        $resource = $this->service->serializeList($this->assembler->entitiesToResources($list));
        return JsonResponse::fromJsonString($resource);
    }

    #[Route('/api/trackingrecord/saveoneapp', 'tracking_record_save_one_app')]
    public function saveOneFromApp(Request $request) : JsonResponse
    {
        $resource = $this->service->validate($request->getContent(), TrackingRecordApp::class);

        if ($resource instanceof TrackingRecordApp) {
            $this->useCases->create($this->assembler->resourceToEntity($resource));
        }
        
        return new JsonResponse(['success'=>true]);
    }

    #[Route('/api/trackingrecord/saveapp', 'tracking_record_saveapp')]
    public function saveFromApp(Request $request): JsonResponse  
    {
        $list = $this->service->validateAll($request->getContent(), TrackingRecordApp::class);

        $erros = [];
        $results = [];

        foreach($list as $item) {
        
            if ($item instanceof TrackingRecordApp) {
                $entity = $this->assembler->resourceToEntity($item);
                $id = $this->useCases->create($entity);
                $results[] = ['id'=>$id, 'deviceId'=>$item->getDeviceId()];
            } else {
                $erros[] = $item;
            }

        }

        return new JsonResponse([
            "results"=>$results, "validations"=>$erros
        ]);
    }
}
