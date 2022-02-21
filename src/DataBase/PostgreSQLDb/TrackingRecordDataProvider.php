<?php

declare(strict_types=1);

namespace App\DataBase\PostgreSQLDb;

use Doctrine\Persistence\ManagerRegistry;
use App\Domain\DataProviderDefinitions\TrackingRecordDataProviderInterface;
use App\DataBase\Entity\TrackingRecord;
use App\DataBase\Utils\EntityMetaData;

class TrackingRecordDataProvider implements TrackingRecordDataProviderInterface
{
    private $doctrine;
    private $em;
    private $entityMetaData;

    public function __construct(ManagerRegistry $doctrine, EntityMetaData $entityMetaData) {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->entityMetaData = $entityMetaData;
    }

    public function save(object $entity): int 
    {
        if (!($entity instanceof TrackingRecord)) {
            throw new \Exception(
                sprintf('An object of type (\'%s\') was expected, but (\'%s\') was received; received!', 
                TrackingRecord::class, 
                get_class($entity))
            );
        }

        $entity->setCreatedAt(new \DateTime());
        $this->em->persist($entity);
        $this->em->flush();

        return $entity->getId();
    }

    public function update(object $entity): void
    {
        if (!($entity instanceof TrackingRecord)) {
            throw new \Exception(
                sprintf('An object of type (\'%s\') was expected, but (\'%s\') was received; received!', 
                TrackingRecord::class, 
                get_class($entity))
            );
        }

        $entity->setUpdatedAt(new \DateTime());
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function find($id): ?object
    {
        $entity = $this->em->getRepository(TrackingRecord::class)->find($id);

        if ($entity === null) {
            throw new \Exception(
                sprintf('Entity not found with ID \'%s\'!', $id)
            );
        }

        return $entity;
    }

    public function remove($id): bool
    {
        $entity = $this->em->getRepository(TrackingRecord::class)->find($id);

        if ($entity === null) {
            throw new \Exception(
                sprintf('Entity not found with ID \'%s\'!', $id)
            );
        }

        $this->em->remove($entity);
        $this->em->flush();

        return true;
    }

    public function findAll(): array
    {
        return $this->em->getRepository(TrackingRecord::class)->findAll();
    }

    public function findByFilter(array $filter): object
    {
        return $this->em->getRepository(TrackingRecord::class)->listFilter($filter);   
    }

}