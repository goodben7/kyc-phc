<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_CODE', fields: ['code'])]
#[ApiResource(
    normalizationContext: ['groups' => 'category:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_CATEGORY_DETAILS")',
            provider: ItemProvider::class
        ),
        new GetCollection(
            security: 'is_granted("ROLE_CATEGORY_LIST")',
            provider: CollectionProvider::class
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'label' => 'ipartial',
    'description' => 'ipartial',
    'code' => 'exact',
    'actived' => 'exact'
])]
class Category
{
    const ID_PREFIX = "CA";

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    #[Groups(groups: ['category:get'])]
    private ?string $id = null;

    #[ORM\Column(length: 120)]
    #[Groups(groups: ['category:get'])]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['category:get'])]
    private ?string $description = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(groups: ['category:get'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(groups: ['category:get'])]
    private ?bool $actived = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isActived(): ?bool
    {
        return $this->actived;
    }

    public function setActived(bool $actived): static
    {
        $this->actived = $actived;

        return $this;
    }
}
