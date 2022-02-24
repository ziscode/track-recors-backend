<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    #[Assert\Type("string")]
    #[Assert\NotBlank(allowNull:true)]
    #[Assert\Length(
        min: 8,
        minMessage: 'Your password must be at least {{ limit }} characters long',
    )]
    #[Assert\Expression(
        "this.getPlainPassword() == this.getRepeatedPassword()",
        message: 'Passwords not match!',
    )]
    protected $plainPassword;

    #[Assert\Type("string")]
    #[Assert\NotBlank(allowNull:true)]
    #[Assert\Length(
        min: 8,
        minMessage: 'Your password must be at least {{ limit }} characters long',
    )]
    protected $repeatedPassword;
    

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRepeatedPassword(): ?string
    {
        return $this->repeatedPassword;
    }

    public function setRepeatedPassword(?string $repeatedPassword): self
    {
        $this->repeatedPassword = $repeatedPassword;

        return $this;
    }
    
}
