<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\DataBase\Entity\User;
use App\Infrastructure\Services\SerializerValidator;
use App\Infrastructure\Assemblers\UsersAssembler;

class SecurityController extends AbstractController
{

    private $service;
    private $assembler;
    
    public function __construct(SerializerValidator $service, 
        UsersAssembler $assembler) 
    {
        $this->service = $service;
        $this->assembler = $assembler;
    }

    #[Route('/login', name: 'api_login')]
    public function loginJson(Request $request) 
    {

        $user = $this->getUser();

        if( $user instanceof User ) {
        
            $resource = $this->service->serialize(
                $this->assembler->entityToResource($user));
    
            return JsonResponse::fromJsonString($resource);
        }

        return new JsonResponse( [
            'message' => 'Login failed! Invalid E-mail and/or password!'
        ]);
    }

    #[Route('/logout', 'api_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/logout/response', 'api_logout_response')]
    public function logoutMessage()
    {
        if ($this->getUser() === null) {
            return new JsonResponse(['logout'=>true]);
        }

        return new JsonResponse(['logout'=>false, 'error'=>'The user is still active in the session']);
    } 

    #[Route('/api/checkuser', name: 'api_chekuser')]
    public function checkuser(Request $request): Response
    {

        $user = $this->getUser();
        
        if (null === $user) {
            return new JsonResponse();
        }

        $resource = $this->service->serialize(
            $this->assembler->entityToResource($user));

        return JsonResponse::fromJsonString($resource);
    }
}
