<?php

namespace App\Entity;

use App\Doctrine\IdGenerator;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\KycDocumentRepository;

#[ORM\Entity(repositoryClass: KycDocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
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
    private ?string $id = null;

    #[ORM\Column(length: 5)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentRefNumber = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(length: 1)]
    private ?string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'kycDocuments')]
    private ?Agent $agent = null;

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
}
