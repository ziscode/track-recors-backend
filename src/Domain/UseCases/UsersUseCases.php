<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\UseCases\UsersUseCasesInterface;
use App\Domain\DataProviderDefinitions\UsersDataProviderInterface;

class UsersUseCases implements UsersUseCasesInterface
{

    /**
     * @var UsersDataProviderInterface
     */
    protected $dataProvider;

    public function __construct(UsersDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function updateProfile(object $object): bool
    {
        return $this->dataProvider->update($object) > 0;
    }

    /**
     * @param string $id
     *
     * @return object
     *
     * @throws Exception
     */
    public function retrieve(int $id): ?object
    {
        $object = $this->dataProvider->find($id);
        
        if (!$object) {
            throw new \Exception(sprintf('Unable to find value with the key \'%s\'', $key));
        }

        return $object;
    }

    public function changeStatus(object $object): bool
    {
        $object->setEnabled(!$object->getEnabled());
        return $this->dataProvider->update($object) > 0;
    }

    public function list(): ?array 
    {
        return $this->dataProvider->findAll();
    }

    public function filter(?array $filter): ?object 
    {
        if ($filter === null) {
            $filter = [];
        }
        
        return $this->dataProvider->findByFilter($filter);
    }

}