<?php

declare (strict_types=1);

namespace App\Infrastructure\Assemblers;

use App\DataBase\Entity\User;
use App\Infrastructure\Dto\UserRegister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserAssembler
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) 
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $userPasswordHasher;
    }

    public function registerResourceToEntity(UserRegister $resource): User 
    {
        $entity = new User();
        
        if ($resource->getId()) {
            $entity = $this->entityManager->getRepository(User::class)->find($resource->getId());
        }

        $entity->setName($resource->getName());
        $entity->setEmail($resource->getEmail());

        if (!empty($resource->getPlainPassword())) {
            $entity->setPassword(
                $this->passwordHasher->hashPassword(
                        $entity,
                        $resource->getPlainPassword()
                    )
                );
        }

        return $entity;
    }

}