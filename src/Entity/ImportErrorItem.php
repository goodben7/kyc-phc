<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ImportErrorItemRepository;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;

#[ORM\Entity(repositoryClass: ImportErrorItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_IMPORT_ERROR_ITEM_LIST')",
            provider: CollectionProvider::class,
        ),
        new Get(
            security: "is_granted('ROLE_IMPORT_ERROR_ITEM_DETAILS')",
            provider: ItemProvider::class
        )
    ],
    normalizationContext: ['groups' => ['item:get']],
)]
#[ApiFilter(SearchFilter::class, properties: ['importId' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
class ImportErrorItem 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['item:get'])]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups(['item:get'])]
    private ?string $importId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['item:get'])]
    private ?string $message = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['item:get'])]
    private ?int $loading = null;

    #[ORM\Column()]
    #[Groups(['item:get'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImportId(): ?string
    {
        return $this->importId;
    }

    public function setImportId(string $importId): static
    {
        $this->importId = $importId;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = substr($message, 0, 255);

        return $this;
    }

    public function getLoading(): ?int
    {
        return $this->loading;
    }

    public function setLoading(?int $loading): static
    {
        $this->loading = $loading;

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
}
