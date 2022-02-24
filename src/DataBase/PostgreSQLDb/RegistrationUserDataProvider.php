<?php

declare(strict_types=1);

namespace App\DataBase\PostgreSQLDb;

use Doctrine\Persistence\ManagerRegistry;
use App\Domain\DataProviderDefinitions\RegistrationUserDataProviderInterface;
use App\DataBase\Entity\User;

class RegistrationUserDataProvider implements RegistrationUserDataProviderInterface
{
    private $doctrine;
    private $em;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    public function save(object $entity): int 
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

    public function locateEmail(string $email): ?object
    {
        return $this->em->getRepository(User::class)->findOneBy(['email'=>$email]);
    }
}