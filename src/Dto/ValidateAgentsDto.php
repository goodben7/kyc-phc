<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ValidateAgentsDto
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public array $agents = [];
}