<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegister
{
    #[Assert\Type("int")]
    protected $id;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    protected $email;

    #[Assert\Type("string")]
    #[Assert\NotBlank(allowNull:true)]
    #[Assert\Length(
        min: 8,
        minMessage: 'Your password must be at least {{ limit }} characters long',
    )]
    #[Assert\Expression(
        "this.checkPassword()",
        message: 'This value not be blank!',
    )]
    protected $plainPassword;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    protected $name;

    
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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

    public function checkPassword()
    {

        if ((int)$this->getId() === 0 && empty($this->getPlainPassword())) {
            return false;
        }

        return true;

    }
}
