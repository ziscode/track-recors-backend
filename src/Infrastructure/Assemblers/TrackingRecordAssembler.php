<?php

declare (strict_types=1);

namespace App\Infrastructure\Assemblers;

use App\DataBase\Entity\ListBase;
use App\DataBase\Entity\TrackingRecord;
use App\Infrastructure\Dto\TrackingRecordList;
use App\Infrastructure\Dto\TrackingRecord as TrackingRecordDto;
use App\Infrastructure\Dto\TrackingRecordApp;
use Doctrine\ORM\EntityManagerInterface;

class TrackingRecordAssembler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function resourceToEntity(TrackingRecordDto $resource): TrackingRecord
    {
        $entity = new TrackingRecord();
        
        if ($resource->getId()) {
            $entity = $this->entityManager->getRepository(TrackingRecord::class)->find($resource->getId());
        }

        $entity->setStartDate(new \DateTime($resource->getStartDate()));
        $entity->setEndDate(new \DateTime($resource->getEndDate()));
        $entity->setInitialCoordinate(['latitude'=>$resource->getStartLatitude(), 'longitude'=>$resource->getStartLongitude()]);
        $entity->setFinalCoordinate(['latitude'=>$resource->getEndLatitude(), 'longitude'=>$resource->getEndLongitude()]);
        $entity->setFinished($resource->getFinished());

        /*$entity->setDeviceId($resource->getDeviceId());
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
        $entity->setFinished($resource->getFinished());*/

        return $entity;
    }

    public function entityToResource(TrackingRecord $entity): TrackingRecordDto
    {
        $resource = new TrackingRecordDto();
        $resource->setId($entity->getId());                      
        $resource->setStartDate($entity->getStartDate()->format('Y-m-d H:i'));
        $resource->setEndDate($entity->getEndDate()->format('Y-m-d H:i'));
        $coordinate = $entity->getInitialCoordinate();
        $resource->setStartLatitude($coordinate['latitude']);        
        $resource->setStartLongitude($coordinate['longitude']);
        $coordinate = $entity->getFinalCoordinate();
        $resource->setEndLatitude($coordinate['latitude']);
        $resource->setEndLongitude($coordinate['longitude']);
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

    public function filterEntityToResources(ListBase $object): array
    {
        $list = [];
        $entities = $object->getList();

        foreach($entities as $entity) {
            $resource = new TrackingRecordList();
            $resource->setId($entity->getId());                
            $resource->setDeviceId($entity->getDeviceId());        
            $resource->setStartDate($entity->getStartDate()->format('Y-m-d H:i'));
            $resource->setEndDate($entity->getEndDate()->format('Y-m-d H:i'));
            $resource->setFinished($entity->getFinished());
            $list[] = $resource;
        }

        return ['numItems' => $object->getNumItems(), 'list'=>$list];
    }
}