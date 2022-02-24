<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $email
     */
    protected $email;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var bool $enabled
     */
    protected $enabled;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
