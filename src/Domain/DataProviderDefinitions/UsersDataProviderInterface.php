<?php
declare(strict_types=1);

namespace App\Domain\DataProviderDefinitions;

interface UsersDataProviderInterface
{
    public function update(object $entity): int;

    public function find($id): ?object;

    public function findAll(): array;

    public function findByFilter(array $filter): object;
    
}
