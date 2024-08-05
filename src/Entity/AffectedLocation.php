<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AffectedLocationRepository;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;

#[ORM\Entity(repositoryClass: AffectedLocationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => 'affected_location:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_AFFECTED_LOCATION_DETAILS")',
            provider: ItemProvider::class
        ),
        new GetCollection(
            security: 'is_granted("ROLE_AFFECTED_LOCATION_LIST")',
            provider: CollectionProvider::class
        ),
        new Post(
            security: 'is_granted("ROLE_AFFECTED_LOCATION_CREATE")',
            denormalizationContext: ['groups' => 'affected_location:post'],
            processor: PersistProcessor::class,
        ),
    ]
)]
class AffectedLocation
{
    const ID_PREFIX = "AL";

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    #[Groups(groups: ['affected_location:get'])]
    private ?string $id = null;

    #[ORM\Column(length: 120)]
    #[Groups(groups: ['affected_location:get', 'affected_location:post'])]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['affected_location:get', 'affected_location:post'])]
    private ?string $description = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(groups: ['affected_location:get', 'affected_location:post'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(groups: ['affected_location:get', 'affected_location:post'])]
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
