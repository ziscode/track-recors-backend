<?php

namespace App\DataBase\Utils;

use Doctrine\ORM\EntityManagerInterface;

class EntityMetaData
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function merge(object $from, object $to): object 
    {
        $classFrom = get_class($from);
        $classTo = get_class($to);        
        if ($classFrom !== $classTo) {
            throw new \Exception(
                sprintf('Unexpected class! Awaited class (\'%s\'), received class (\'%s\').', 
                $classFrom, 
                $classTo)
            );
        }
        
        $metaData = $this->entityManager->getClassMetaData($classFrom);
        $mappings = $metaData->fieldMappings;

        foreach($mappings as $k => $mapping) {
            $set = "set".ucfirst($k);
            $get = "get".ucfirst($k);

            if (method_exists($from, $set) && method_exists($from, $get) 
                && $from->$get() !== $to->$get()) {

                $to->$set($from->$get());
            }
        }
        
    }
}