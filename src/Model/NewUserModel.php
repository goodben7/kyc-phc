<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class NewUserModel
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public ?string $phone = null,

        #[Assert\NotNull]
        #[Assert\NotBlank]
        public ?string $plainPassword = null, 

        #[Assert\NotBlank()]
        #[Assert\NotNull()]
        #[Assert\Choice(callback: [User::class, 'getAvailablesRoles'])]
        public ?string $roles = null,

        public ?string $displayName = null,

    )
    {  
    }
}