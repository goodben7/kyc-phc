<?php

namespace App\Dto;

use App\Entity\Import;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateImportDto
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [Import::TYPE_AGENT, Import::TYPE_DOCUMENT])]
    public ?string $type = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [Import::METHOD_CREATE, Import::METHOD_UPDATE])]
    public ?string $method = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public UploadedFile $file;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?string $description = null;

    public ?string $data3 = null;

    public ?string $data4 = null;

    public ?string $data5 = null;

    public ?string $data6 = null;
}