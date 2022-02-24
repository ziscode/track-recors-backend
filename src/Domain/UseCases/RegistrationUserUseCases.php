<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\UseCases\RegistrationUserUseCasesInterface;
use App\Domain\DataProviderDefinitions\RegistrationUserDataProviderInterface;

class RegistrationUserUseCases implements RegistrationUserUseCasesInterface
{

    /**
     * @var RegistrationUserDataProviderInterface
     */
    protected $dataProvider;

    public function __construct(RegistrationUserDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function register(object $object): array
    {
        if ( $this->checkEmail($object->getId(), $object->getEmail()) ) {
            $id = $this->dataProvider->save($object);

            if ($id > 0) {
                return ['success'=>true];
            }

            return ['error'=>'User not registered!'];
        } 

        return ['error'=>'This email is already in use!'];
    }

    public function update(object $object): array
    {
        if ( $this->checkEmail($object->getId(), $object->getEmail()) ) {
            $id = $this->dataProvider->update($object);

            if ($id > 0) {
                return ['success'=>true];
            }

            return ['error'=>'User not updated!'];
        } 

        return ['error'=>'This email is already in use!'];
    }

    private function checkEmail(?int $id, string $email): bool
    {   
        $object = $this->dataProvider->locateEmail($email);
        return $object === null || $object->getId() === $id ;
    }
}