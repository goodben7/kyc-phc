<?php

namespace App\Model;

use App\Entity\Agent;
use Symfony\Component\Validator\Constraints as Assert;

class NewKycDocumentModel
{

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?string $documentType = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?string $documentRefNumber = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?Agent $agent = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $file;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?string $externalReferenceId = null;
}