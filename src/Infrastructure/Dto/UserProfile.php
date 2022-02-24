<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Dto\UserRegister;

class UserProfile extends UserRegister
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
    protected $repeatedPassword;

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
