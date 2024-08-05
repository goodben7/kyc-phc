<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FunctionTitleRepository;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;

#[ORM\Entity(repositoryClass: FunctionTitleRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_CODE', fields: ['code'])]
#[ApiResource(
    normalizationContext: ['groups' => 'function:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_FUNCTION_DETAILS")',
            provider: ItemProvider::class
        ),
        new GetCollection(
            security: 'is_granted("ROLE_FUNCTION_LIST")',
            provider: CollectionProvider::class
        ),
        new Post(
            security: 'is_granted("ROLE_FUNCTION_CREATE")',
            denormalizationContext: ['groups' => 'function:post'],
            processor: PersistProcessor::class,
        ),
        new Patch(
            security: 'is_granted("ROLE_FUNCTION_UPDATE")',
            denormalizationContext: ['groups' => 'function:patch'],
            processor: PersistProcessor::class,
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'label' => 'ipartial',
    'description' => 'ipartial',
    'code' => 'exact',
    'actived' => 'exact'
])]
class FunctionTitle
{
    const ID_PREFIX = "FU";

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[Groups(groups: ['function:get'])]
    #[ORM\Column(length: 16)]
    private ?string $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups(groups: ['function:get', 'function:post', 'function:patch'])]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['function:get', 'function:post', 'function:patch'])]
    private ?string $description = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(groups: ['function:get', 'function:post', 'function:patch'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(groups: ['function:get', 'function:post', 'function:patch'])]
    private ?bool $actived = true;

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

    public function setDescription(?string $description): static
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
