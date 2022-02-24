<?php

declare(strict_types=1);

namespace App\DataBase\PostgreSQLDb;

use Doctrine\Persistence\ManagerRegistry;
use App\Domain\DataProviderDefinitions\UsersDataProviderInterface;
use App\DataBase\Entity\User;

class UsersDataProvider implements UsersDataProviderInterface
{
    private $doctrine;
    private $em;
    
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    public function update(object $entity): int 
    {
        if (!($entity instanceof User)) {
            throw new \Exception(
                sprintf('An object of type (\'%s\') was expected, but (\'%s\') was received; received!', 
                User::class, 
                get_class($entity))
            );
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    public function find($id): ?object
    {
        $entity = $this->em->getRepository(User::class)->find($id);

        if ($entity === null) {
            throw new \Exception(
                sprintf('Entity not found with ID \'%s\'!', $id)
            );
        }

        return $entity;
    }
    
    public function findAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function findByFilter(array $filter): object
    {
        return $this->em->getRepository(User::class)->listFilter($filter);   
    }

}