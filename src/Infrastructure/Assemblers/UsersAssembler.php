<?php

declare (strict_types=1);

namespace App\Infrastructure\Assemblers;

use App\DataBase\Entity\User;
use App\Infrastructure\Dto\User as UserResource;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Dto\UserProfile;
use App\DataBase\Entity\ListBase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersAssembler
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) 
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $userPasswordHasher;
    }

    public function resourceToEntity(UserResource $resource): User 
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

    public function entityToResource(User $entity): UserResource
    {
        $resource = new UserResource();
        $resource->setId($entity->getId());
        $resource->setName($entity->getName());
        $resource->setEmail($entity->getEmail());
        $resource->setEnabled($entity->getEnabled());

        return $resource;
    }

    public function entitiesToResources(array $entities): array
    {
        $resources = [];

        foreach($entities as $entity) {
            $resources[] = $this->entityToResource($entity);
        }

        return $resources;
    }

    public function filterEntityToResources(ListBase $object): array
    {
        $list = [];
        $entities = $object->getList();

        foreach($entities as $entity) {
            $list[] = $this->entityToResource($entity);
        }
        
        return ['numItems' => $object->getNumItems(), 'list'=>$list];
    }

    public function resourceToEntityProfile(UserProfile $resource, User $entity): User 
    {
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