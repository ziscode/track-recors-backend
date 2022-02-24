<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\Services\SerializerValidator;
use App\Infrastructure\Dto\Users;
use App\Infrastructure\Dto\UserProfile;
use App\Domain\UseCases\UsersUseCases;
use App\Infrastructure\Assemblers\UsersAssembler;

class UsersController extends AbstractController
{

    private $service;
    private $assembler;
    private $useCases;

    public function __construct(SerializerValidator $service, 
        UsersAssembler $assembler, UsersUseCases $useCases) 
    {
        $this->service = $service;
        $this->assembler = $assembler;
        $this->useCases = $useCases;
    }
    
    #[Route('api/users/list', 'users_list')]
    public function list(Request $request): JsonResponse
    {
        $object = $this->useCases->filter(json_decode($request->getContent(), true));
        $resource = $this->service->serializeList($this->assembler->filterEntityToResources($object));
        return JsonResponse::fromJsonString($resource);
    }

    #[Route('api/users/find/{id}', 'users_find')]
    public function find(Request $request, $id): JsonResponse
    {
        $entity = $this->useCases->retrieve((int)$id);
        $resource = $this->service->serialize(
            $this->assembler->entityToResource($entity));

        return JsonResponse::fromJsonString($resource);
    }

    #[Route('api/users/updatestatus/{id}', 'users_updatestatus')]
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $entity = $this->useCases->retrieve((int)$id);
        $result = $this->useCases->changeStatus($entity);
        return new JsonResponse(['success'=>$result]);
    }

    #[Route('api/retrieve/profile', 'retrieve_profile')]
    public function retrieveProfile(Request $request): JsonResponse
    {        
        $resource = $this->service->serialize(
            $this->assembler->entityToResource($this->getUser()));

        return JsonResponse::fromJsonString($resource);
    }

    #[Route('api/update/profile', 'update_profile')]
    public function updateProfile(Request $request): JsonResponse
    {
        $resource = $this->service->validate($request->getContent(), UserProfile::class);
        
        if ($resource instanceof UserProfile) {
            $entity = $this->assembler->resourceToEntityProfile($resource, $this->getUser());            
            $this->useCases->updateProfile($entity);

            return new JsonResponse(['success'=>true]);
        }
        
        return new JsonResponse($resource);
    }
    
}
