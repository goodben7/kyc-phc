<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\KycDocumentRepository;
use App\State\VerifyKycDocumentProcessor;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: KycDocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => 'kycdoc:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_KYCDOC_DETAILS")',
            provider: ItemProvider::class,
        ),
        new GetCollection(
            security: 'is_granted("ROLE_KYCDOC_LIST")',
            provider: CollectionProvider::class,
        ),
        new Post(
            denormalizationContext: ['groups' => 'kycdoc:post'],
            security: 'is_granted("ROLE_KYCDOC_CREATE")',
            inputFormats: ['multipart' => ['multipart/form-data']],
            processor: PersistProcessor::class,
        ),
        new Post(
            denormalizationContext: ['groups' => 'kycdoc:validate'],
            uriTemplate: "kyc_documents/{id}/verify",
            security: 'is_granted("ROLE_KYCDOC_VERIFY")',
            processor: VerifyKycDocumentProcessor::class,
            validationContext: ['groups' => ['verify_doc']],
            status: 200
        )
    ]
)]
class KycDocument
{
    const ID_PREFIX = "KD";

    const TYPE_ID = "ID";
    const TYPE_PASSPORT = "PASS";
    const TYPE_PASSPORT_PHOTO = "PSSPH";
    const TYPE_DRIVE_LICENCE = "DRVLC";
    const TYPE_SIGNATURE = "SIGNT";
    const TYPE_OTHER = "OTHER";
    
    const STATUS_VERIFIED = "V";
    const STATUS_PENDING = "P";
    const STATUS_REFUSED = "R";


    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    #[Groups(groups: ['kycdoc:get'])]
    private ?string $id = null;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Choice(callback: "getTypeAsChoices")]
    #[Groups(groups: ['kycdoc:get', 'kycdoc:post'])]
    #[ORM\Column(length: 5)]
    private ?string $type = null;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(groups: ['kycdoc:get', 'kycdoc:post'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentRefNumber = null;

    #[ORM\Column]
    #[Groups(groups: ['kycdoc:get'])]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(length: 1)]
    #[Groups(groups: ['kycdoc:get'])]
    private ?string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['kycdoc:get'])]
    private ?string $filePath = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['kycdoc:get'])]
    private ?int $fileSize = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['kycdoc:get'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'kycDocuments')]
    #[Groups(groups: ['kycdoc:get', 'kycdoc:post'])]
    private ?Agent $agent = null;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(groups: ['kycdoc:post'])]
    #[Vich\UploadableField(mapping: 'media_object', fileNameProperty: 'filePath', size: 'fileSize')]
    private ?File $file = null;

    #[Groups(groups: ['kycdoc:get'])]
    private ?string $contentUrl;

    #[Assert\Type('bool', groups: ['verify_doc'])]
    #[Assert\NotBlank(groups: ['verify_doc'])]
    #[Assert\NotNull(groups: ['verify_doc'])]
    #[Groups(groups: ['kycdoc:validate'])]
    public bool $verified;

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

    public function getDocumentRefNumber(): ?string
    {
        return $this->documentRefNumber;
    }

    public function setDocumentRefNumber(?string $documentRefNumber): static
    {
        $this->documentRefNumber = $documentRefNumber;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): static
    {
        $this->fileSize = $fileSize;

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

    public static function getTypeAsChoices(): array {
        return [
            "Pièce d'identité" => self::TYPE_ID,
            "Permis de conduire" => self::TYPE_DRIVE_LICENCE,
            "Passport" => self::TYPE_PASSPORT,
            "Photo Passport" => self::TYPE_PASSPORT_PHOTO,
            "Signature" => self::TYPE_SIGNATURE,
            "Autres" => self::TYPE_OTHER,
        ];
    }

    public static function getStatusAsChoices(): array {
        return [
            "Vérifié" => self::STATUS_VERIFIED,
            "En attente" => self::STATUS_PENDING,
            "Réfusé" => self::STATUS_REFUSED,
        ];
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->uploadedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get the value of contentUrl
     */ 
    public function getContentUrl()
    {
        return $this->contentUrl;
    }

    /**
     * Set the value of contentUrl
     *
     * @return  self
     */ 
    public function setContentUrl($contentUrl)
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }
}
