<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

interface RegistrationUserUseCasesInterface
{
    public function register(object $object): array;

    public function update(object $object): array;

}


