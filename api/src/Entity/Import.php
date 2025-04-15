<?php

namespace App\Entity;

use App\Dto\CreateImportDto;
use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use ApiPlatform\Metadata\Post;
use App\Model\ImportInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ImportRepository;
use App\State\CreateImportProcessor;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;

#[ORM\Entity(repositoryClass: ImportRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => 'import:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_IMPORT_DETAILS")',
            provider: ItemProvider::class
        ),
        new GetCollection(
            security: 'is_granted("ROLE_IMPORT_LIST")',
            provider: CollectionProvider::class
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            security: 'is_granted("ROLE_IMPORT_CREATE")',
            input: CreateImportDto::class,
            processor: CreateImportProcessor::class,
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'status' => 'exact',
    'type' => 'exact',
    'method' => 'exact',
    'createdBy' => 'exact'
])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
class Import implements ImportInterface
{
    const ID_PREFIX = "IM";

    const STATUS_IDLE = 'I';
    const STATUS_INPROGRESS = 'P';
    const STATUS_TERMINATED = 'T';
    const STATUS_LOADED = 'L';
    const STATUS_FAILED = 'F';

    const METHOD_CREATE = 'C';
    const METHOD_UPDATE = 'U'; 

    const TYPE_AGENT ='AGENT';
    const TYPE_DOCUMENT = 'DOCUMENT';

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    #[Groups(groups: ['import:get'])]
    private ?string $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(groups: ['import:get'])]
    private ?string $type = null;

    #[ORM\Column(length: 15)]
    #[Groups(groups: ['import:get'])]
    private ?string $method = null;

    #[ORM\Column(length: 1)]
    #[Groups(groups: ['import:get'])]
    private ?string $status = self::STATUS_IDLE;

    #[ORM\Column(length: 16)]
    #[Groups(groups: ['import:get'])]
    private ?string $createdBy = null;

    #[ORM\Column]
    #[Groups(groups: ['import:get'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $message = null;

    #[ORM\Column]
    #[Groups(groups: ['import:get'])]
    private ?int $loaded = 0;

    #[ORM\Column]
    #[Groups(groups: ['import:get'])]
    private ?int $treated = 0;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data5 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $data6 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['import:get'])]
    private ?string $description = null;

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

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): static
    {
        $this->createdBy = $createdBy;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getLoaded(): ?int
    {
        return $this->loaded;
    }

    public function setLoaded(int $loaded): static
    {
        $this->loaded = $loaded;

        return $this;
    }

    public function getTreated(): ?int
    {
        return $this->treated;
    }

    public function setTreated(int $treated): static
    {
        $this->treated = $treated;

        return $this;
    }

    public function getData1(): ?string
    {
        return $this->data1;
    }

    public function setData1(?string $data1): static
    {
        $this->data1 = $data1;

        return $this;
    }

    public function getData2(): ?string
    {
        return $this->data2;
    }

    public function setData2(?string $data2): static
    {
        $this->data2 = $data2;

        return $this;
    }

    public function getData3(): ?string
    {
        return $this->data3;
    }

    public function setData3(?string $data3): static
    {
        $this->data3 = $data3;

        return $this;
    }

    public function getData4(): ?string
    {
        return $this->data4;
    }

    public function setData4(?string $data4): static
    {
        $this->data4 = $data4;

        return $this;
    }

    public function getData5(): ?string
    {
        return $this->data5;
    }

    public function setData5(?string $data5): static
    {
        $this->data5 = $data5;

        return $this;
    }

    public function getData6(): ?string
    {
        return $this->data6;
    }

    public function setData6(?string $data6): static
    {
        $this->data6 = $data6;

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
}
