<?php
declare(strict_types=1);

namespace App\Domain\DataProviderDefinitions;

interface RegistrationUserDataProviderInterface
{
    public function save(object $entity): int;

    public function update(object $entity): int;

    public function locateEmail(string $email): ?object;
}
