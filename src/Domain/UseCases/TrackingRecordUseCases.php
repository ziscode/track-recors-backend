<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\UseCases\CrudUseCasesInterface;
use App\Domain\DataProviderDefinitions\TrackingRecordDataProviderInterface;

class TrackingRecordUseCases implements CrudUseCasesInterface
{

    /**
     * @var TrackingRecordDataProviderInterface
     */
    protected $dataProvider;

    public function __construct(TrackingRecordDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function create(object $object): int
    {
        return $this->dataProvider->save($object);
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

    public function update(object $object): void
    {
        $this->dataProvider->update($object);
    }

    public function delete(int $id): void
    {
        $this->dataProvider->remove($id);
    }

    public function list(): ?array 
    {
        return $this->dataProvider->findAll();
    }

}