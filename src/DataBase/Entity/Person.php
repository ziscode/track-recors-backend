<?php

declare(strict_types=1);

namespace App\DataBase\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Person
{

    /**
     * @var string
     */
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    private $name;

    /**
     * @var int
     */
    #[Assert\Type("int")]
    #[Assert\NotBlank]
    private $age;

    /**
     * @var string
     */
    #[Assert\Type("string")]
    private $doc;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }
    
    public function setAge(int $age)
    {
        $this->age = $age;
    }
    public function getDoc(): ?string
    {
        return $this->doc;
    }
    
    public function setDoc(?string $doc)
    {
        $this->doc = $doc;
    }
    

    
}