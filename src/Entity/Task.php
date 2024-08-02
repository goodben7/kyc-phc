<?php

namespace App\Entity;

use App\Doctrine\IdGenerator;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    const ID_PREFIX = "TA";

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    private ?string $id = null;

    #[ORM\Column(length: 30)]
    private ?string $type = null;

    #[ORM\Column(length: 15)]
    private ?string $method = null;

    #[ORM\Column(length: 1)]
    private ?string $status = null;

    #[ORM\Column(type: Types::JSON)]
    private array $data = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 16)]
    private ?string $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $updatedBy = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $synchronizedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $synchronizedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getSynchronizedBy(): ?string
    {
        return $this->synchronizedBy;
    }

    public function setSynchronizedBy(?string $synchronizedBy): static
    {
        $this->synchronizedBy = $synchronizedBy;

        return $this;
    }

    public function getSynchronizedAt(): ?\DateTimeImmutable
    {
        return $this->synchronizedAt;
    }

    public function setSynchronizedAt(?\DateTimeImmutable $synchronizedAt): static
    {
        $this->synchronizedAt = $synchronizedAt;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
