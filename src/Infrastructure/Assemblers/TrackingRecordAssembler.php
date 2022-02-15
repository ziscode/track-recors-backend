<?php

namespace App\Infrastructure\Assemblers;

use App\DataBase\Entity\TrackingRecord;
use App\Infrastructure\Dto\TrackingRecordWeb;
use App\Infrastructure\Dto\TrackingRecord as TrackingRecordResource;
use Doctrine\ORM\EntityManagerInterface;

class TrackingRecordAssembler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function resourceToEntity(TrackingRecordResource $resource): TrackingRecord
    {
        $entity = new TrackingRecord();

        if ($resource instanceof TrackingRecordWeb && $resource->getId() > 0) {
            $entity = $this->entityManager->getRepository(TrackingRecord::class)->find($resource->getId());
        }

        $entity->setDeviceId($resource->getDeviceId());
        $d = new \DateTime();
        $d->setTimestamp($resource->getStartDate());
        $entity->setStartDate($d);
        $d = new \DateTime();
        $d->setTimestamp($resource->getEndDate());
        $entity->setEndDate($d);
        $entity->setInitialCoordinate($resource->getInitialCoordinate());
        $entity->setFinalCoordinate($resource->getFinalCoordinate());
        $entity->setTracking($resource->getTracking());
        $entity->setTrackingInfo($resource->getTrackingInfo());
        $entity->setFinished($resource->getFinished());

        return $entity;
    }

    public function entityToResource(TrackingRecord $entity): TrackingRecordWeb
    {
        $resource = new TrackingRecordWeb();
        $resource->setId($entity->getId());                
        $resource->setDeviceId($entity->getDeviceId());        
        $resource->setStartDate($entity->getStartDate()->getTimestamp());
        $resource->setEndDate($entity->getEndDate()->getTimestamp());
        $resource->setInitialCoordinate($entity->getInitialCoordinate());
        $resource->setFinalCoordinate($entity->getFinalCoordinate());
        $resource->setTracking($entity->getTracking());
        $resource->setTrackingInfo($entity->getTrackingInfo());
        $resource->setFinished($entity->getFinished());
        
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
}