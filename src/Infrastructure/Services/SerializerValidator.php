<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class SerializerValidator 
{

    private $validator;
    
    public function __construct(ValidatorInterface $validator) 
    {
        $this->validator = $validator;
    }

    public function validate(string $data, string $class)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        
        try {
            $object = $serializer->deserialize($data, $class, 'json');
        } catch (\Exception $e) {
            return ['erros' => ['deserialization'=>$e->getMessage()], 'data'=>json_decode($data, true)];
        }

        if (!empty($errors = $this->validateData($object))) {
            return ['errors'=>$errors, 'data'=>json_decode($this->serialize($object), true)];
        }

        return $object;
    }

    public function validateAll(string $data, string $class) 
    {
        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );

        $list = [];
        
        try {
            $deserializeList = $serializer->deserialize($data, $class.'[]', 'json');

            foreach($deserializeList as $item) {
                
                $errors = $this->validateData($item);
                
                if (!empty($errors = $this->validateData($item))) {
                    $list[] = ['errors'=>$errors, 'data'=>json_decode($this->serialize($item), true)];
                } else {
                    $list[] = $item;
                }
            }

        } catch (\Exception $e) {
            return ['erros' => ['deserialization'=>$e->getMessage()], 'data'=>json_decode($data, true)];
        }
        
        return $list;
    }

    private function validateData(object $object): array 
    {
        $errorsList = $this->validator->validate($object);
        $errors = [];

        if (!empty($errorsList)) {
            foreach($errorsList as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errors;
    }

    public function serialize(object $object): string 
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($object, 'json');
    }

    public function serializeList(array $list): string 
    {
        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );

        return $serializer->serialize($list, 'json');
    }
}