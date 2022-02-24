<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

interface UsersUseCasesInterface
{
    public function updateProfile(object $object): bool;

    public function retrieve(int $id): ?object;

    public function changeStatus(object $id): bool;
    
    public function list(): ?array;

    public function filter(?array $filter): ?object;
}
