<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

interface CrudUseCasesInterface
{
    public function create(object $object): int;

    public function retrieve(int $id): ?object;

    public function update(object $object): void;

    public function delete(int $id): void;
    
    public function list(): ?array;
}
