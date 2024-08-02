<?php

namespace App\Dto;

use App\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTasktDto
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [Task::TYPE_AGENT, Task::TYPE_DOCUMENT])]
    public ?string $type = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [Task::METHOD_CREATE, Task::METHOD_UPDATE])]
    public ?string $method = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public array $data = [];

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(max: 16)]
    public ?string $createdBy = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?string $externalReferenceId = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public ?\DateTimeImmutable $createdAt = null;

    public ?string $data1 = null;

    public ?string $data2 = null;

    public ?string $data3 = null;

    public ?string $data4 = null;

    public ?string $data5 = null;

    public ?string $data6 = null;

}