<?php
declare(strict_types=1);

namespace App\Domain\DataProviderDefinitions;

interface TrackingRecordDataProviderInterface
{
    public function save(object $entity): int;

    public function update(object $entity): void;

    public function find($id): ?object;

    public function findAll(): array;

    public function remove($id): bool;
}
