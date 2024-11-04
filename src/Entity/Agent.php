<?php

namespace App\Entity;

use App\Model\NewAgentModel;
use ApiPlatform\Metadata\Get;
use App\Doctrine\IdGenerator;
use ApiPlatform\Metadata\Post;
use App\Dto\ValidateAgentsDto;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\AgentRepository;
use App\State\CreateAgentProcessor;
use App\State\DeleteAgentProcessor;
use ApiPlatform\Metadata\ApiResource;
use App\State\ValidateAgentProcessor;
use App\State\ValidateAgentsProcessor;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_IDENTIFICATION_NUMBER', fields: ['identificationNumber'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EXTERNAL_REFERENCE_ID', fields: ['externalReferenceId'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => 'agent:get'],
    operations: [
        new Get(
            security: 'is_granted("ROLE_AGENT_DETAILS")',
            provider: ItemProvider::class
        ),
        new GetCollection(
            security: 'is_granted("ROLE_AGENT_LIST")',
            provider: CollectionProvider::class
        ),
        new Post(
            security: 'is_granted("ROLE_AGENT_CREATE")',
            input: NewAgentModel::class,
            processor: CreateAgentProcessor::class,
        ),
        new Patch(
            denormalizationContext: ['groups' => 'agent:patch'],
            security: 'is_granted("ROLE_AGENT_UPDATE")',
            processor: PersistProcessor::class,
        ),
        new Delete(
            security: 'is_granted("ROLE_AGENT_DELETE")',
            processor: DeleteAgentProcessor::class
        ),
        new Post(
            denormalizationContext: ['groups' => 'agent:validate'],
            uriTemplate: "agents/{id}/validation",
            security: 'is_granted("ROLE_AGENT_VALIDATE")',
            processor: ValidateAgentProcessor::class,
            status: 200
        ),
        new Post(
            uriTemplate: "agents/validations",
            security: 'is_granted("ROLE_AGENT_VALIDATE")',
            input: ValidateAgentsDto::class,
            processor: ValidateAgentsProcessor::class,
            status: 200
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'firstName' => 'ipartial',
    'lastName' => 'ipartial',
    'postName' => 'ipartial',
    'fullName' => 'ipartial',
    'status' => 'exact',
    'kycStatus' => 'exact',
    'deleted' => 'exact',
    'validatedBy' => 'exact',
    'createdBy' => 'exact',
    'updatedBy' => 'exact',
    'oldIdentificationNumber' => 'exact',
    'identificationNumber' => 'exact',
    'contractualNetPayUsd' => 'exact',
    'contractualNetPayCdf' => 'exact',
    'contratType' => 'exact',
    'annotation' => 'ipartial',
    'placeBirth' => 'ipartial',
    'socialSecurityId' => 'exact',
    'taxIdNumber' => 'exact',
    'bankAccountId' => 'exact',
    'dependent' => 'exact',
    'emergencyContactPerson' => 'ipartial',
    'factSheet' => 'exact',
    'onemValidatedContract' => 'exact',
    'birthCertificate' => 'exact',
    'marriageLicense' => 'exact',
    'site' => 'exact',
    'category' => 'exact',
    'functionTitle' => 'exact',
    'affectedLocation' => 'exact',
    'division' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt', 'validatedAt', 'dateHire', 'endContractDate'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt', 'validatedAt', 'dateHire', 'endContractDate'])]
class Agent
{
    const ID_PREFIX = "AG";

    const KYC_STATUS_VERIFIED = 'V';
    const KYC_STATUS_NOT_VERIFIED = 'N';
    const KYC_STATUS_IN_PROGRESS = 'P';

    const GENDER_MALE = "M";
    const GENDER_FEMALE = "F";
    const GENDER_OTHER = "U";

    const STATUS_VALIDATE = "V";
    const STATUS_PENDING = "P";

    const MARITAL_STATUS_MARRIED = "M";
    const MARITAL_STATUS_DIVORCED = "D";
    const MARITAL_STATUS_SINGLE = "S";
    const MARITAL_STATUS_WIDOWER = "W";

    const CONTRAT_TYPE_CDI = 'CDI';
    const CONTRAT_TYPE_CDD = 'CDD';

    #[ORM\Id]
    #[ORM\GeneratedValue( strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(IdGenerator::class)]
    #[ORM\Column(length: 16)]
    #[Groups(groups: ['agent:get', 'kycdoc:get'])]
    private ?string $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 30)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $postName = null;

    #[ORM\Column(length: 120)]
    #[Groups(groups: ['agent:get', 'kycdoc:get'])]
    private ?string $fullName = null;

    #[ORM\Column(length: 2, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column(length: 1)]
    #[Groups(groups: ['agent:get'])]
    private ?string $kycStatus = null;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $maritalStatus = null;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $gender = null;

    #[ORM\Column(length: 1)]
    #[Groups(groups: ['agent:get'])]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(groups: ['agent:get'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?string $externalReferenceId = null;

    #[ORM\Column(length: 16)]
    #[Groups(groups: ['agent:get'])]
    private ?string $createdBy = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 16, nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?string $updatedBy = null;

    #[ORM\Column(length: 255,nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?string $identificationNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $address2 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?bool $deleted = false;

    #[ORM\Column(length: 16, nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?string $validatedBy = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get'])]
    private ?\DateTimeImmutable $validatedAt = null;

    /**
     * @var Collection<int, KycDocument>
     */
    #[ORM\OneToMany(targetEntity: KycDocument::class, mappedBy: 'agent')]
    private Collection $kycDocuments;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $oldIdentificationNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $contact2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $contractualNetPayUsd = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $contractualNetPayCdf = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?\DateTimeImmutable $dateHire = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $contratType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?\DateTimeImmutable $endContractDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $annotation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $placeBirth = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $socialSecurityId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $taxIdNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $bankAccountId = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?int $dependent = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?string $emergencyContactPerson = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?bool $factSheet = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?bool $onemValidatedContract = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?bool $birthCertificate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?bool $marriageLicense = null;

    #[ORM\ManyToOne(inversedBy: 'agents', cascade: ['persist'])]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?Site $site = null;

    #[ORM\ManyToOne(inversedBy: 'agents', cascade: ['persist'])]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'agents', cascade: ['persist'])]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?FunctionTitle $functionTitle = null;

    #[ORM\ManyToOne(inversedBy: 'agents', cascade: ['persist'])]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?AffectedLocation $affectedLocation = null;

    #[ORM\ManyToOne(inversedBy: 'agents', cascade: ['persist'])]
    #[Groups(groups: ['agent:get', 'agent:patch'])]
    private ?Division $division = null;

    public function __construct()
    {
        $this->kycDocuments = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPostName(): ?string
    {
        return $this->postName;
    }

    public function setPostName(string $postName): static
    {
        $this->postName = $postName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getKycStatus(): ?string
    {
        return $this->kycStatus;
    }

    public function setKycStatus(string $kycStatus): static
    {
        $this->kycStatus = $kycStatus;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): static
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExternalReferenceId(): ?string
    {
        return $this->externalReferenceId;
    }

    public function setExternalReferenceId(?string $externalReferenceId): static
    {
        $this->externalReferenceId = $externalReferenceId;

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

    public function setUpdatedBy(string $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(string $identificationNumber): static
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public static function getKycStatusAsChoices(): array {
        return [
            "in-progress" => self::KYC_STATUS_IN_PROGRESS,
            "verified" => self::KYC_STATUS_VERIFIED,
            "not verified" => self::KYC_STATUS_NOT_VERIFIED,
        ];
    }

    public static function getGenderAsChoices(): array {
        return [
            "Masculin" => self::GENDER_MALE,
            "Feminin" => self::GENDER_FEMALE,
            "Autres" => self::GENDER_OTHER,
        ];
    }

    public static function getStatusAsChoices(): array {
        return [
            "validé" => self::STATUS_VALIDATE,
            "en attente" => self::STATUS_PENDING,
        ];
    }

    public static function getMaritalStatusAsChoices(): array {
        return [
            "Divorcé" => self::MARITAL_STATUS_DIVORCED,
            "Marié" => self::MARITAL_STATUS_MARRIED,
            "Célibataire" => self::MARITAL_STATUS_SINGLE,
            "Veuf/Veuve" => self::MARITAL_STATUS_WIDOWER,
        ];
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function buildFullName()
    {
        $this->fullName = ucfirst($this->firstName) . ' ' . strtoupper($this->lastName) . ' ' . strtoupper($this->postName);
    
        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getValidatedBy(): ?string
    {
        return $this->validatedBy;
    }

    public function setValidatedBy(?string $validatedBy): static
    {
        $this->validatedBy = $validatedBy;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?\DateTimeImmutable $validatedAt): static
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    /**
     * @return Collection<int, KycDocument>
     */
    public function getKycDocuments(): Collection
    {
        return $this->kycDocuments;
    }

    public function addKycDocument(KycDocument $kycDocument): static
    {
        if (!$this->kycDocuments->contains($kycDocument)) {
            $this->kycDocuments->add($kycDocument);
            $kycDocument->setAgent($this);
        }

        return $this;
    }

    public function removeKycDocument(KycDocument $kycDocument): static
    {
        if ($this->kycDocuments->removeElement($kycDocument)) {
            // set the owning side to null (unless already changed)
            if ($kycDocument->getAgent() === $this) {
                $kycDocument->setAgent(null);
            }
        }

        return $this;
    }

    public function getOldIdentificationNumber(): ?string
    {
        return $this->oldIdentificationNumber;
    }

    public function setOldIdentificationNumber(?string $oldIdentificationNumber): static
    {
        $this->oldIdentificationNumber = $oldIdentificationNumber;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContact2(): ?string
    {
        return $this->contact2;
    }

    public function setContact2(?string $contact2): static
    {
        $this->contact2 = $contact2;

        return $this;
    }

    public function getContractualNetPayUsd(): ?string
    {
        return $this->contractualNetPayUsd;
    }

    public function setContractualNetPayUsd(?string $contractualNetPayUsd): static
    {
        $this->contractualNetPayUsd = $contractualNetPayUsd;

        return $this;
    }

    public function getContractualNetPayCdf(): ?string
    {
        return $this->contractualNetPayCdf;
    }

    public function setContractualNetPayCdf(?string $contractualNetPayCdf): static
    {
        $this->contractualNetPayCdf = $contractualNetPayCdf;

        return $this;
    }

    public function getDateHire(): ?\DateTimeImmutable
    {
        return $this->dateHire;
    }

    public function setDateHire(?\DateTimeImmutable $dateHire): static
    {
        $this->dateHire = $dateHire;

        return $this;
    }

    public function getContratType(): ?string
    {
        return $this->contratType;
    }

    public function setContratType(?string $contratType): static
    {
        $this->contratType = $contratType;

        return $this;
    }

    public function getEndContractDate(): ?\DateTimeImmutable
    {
        return $this->endContractDate;
    }

    public function setEndContractDate(?\DateTimeImmutable $endContractDate): static
    {
        $this->endContractDate = $endContractDate;

        return $this;
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function setAnnotation(?string $annotation): static
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getPlaceBirth(): ?string
    {
        return $this->placeBirth;
    }

    public function setPlaceBirth(?string $placeBirth): static
    {
        $this->placeBirth = $placeBirth;

        return $this;
    }

    public function getSocialSecurityId(): ?string
    {
        return $this->socialSecurityId;
    }

    public function setSocialSecurityId(?string $socialSecurityId): static
    {
        $this->socialSecurityId = $socialSecurityId;

        return $this;
    }

    public function getTaxIdNumber(): ?string
    {
        return $this->taxIdNumber;
    }

    public function setTaxIdNumber(?string $taxIdNumber): static
    {
        $this->taxIdNumber = $taxIdNumber;

        return $this;
    }

    public function getBankAccountId(): ?string
    {
        return $this->bankAccountId;
    }

    public function setBankAccountId(?string $bankAccountId): static
    {
        $this->bankAccountId = $bankAccountId;

        return $this;
    }

    public function getDependent(): ?int
    {
        return $this->dependent;
    }

    public function setDependent(?int $dependent): static
    {
        $this->dependent = $dependent;

        return $this;
    }

    public function getEmergencyContactPerson(): ?string
    {
        return $this->emergencyContactPerson;
    }

    public function setEmergencyContactPerson(?string $emergencyContactPerson): static
    {
        $this->emergencyContactPerson = $emergencyContactPerson;

        return $this;
    }

    public function isFactSheet(): ?bool
    {
        return $this->factSheet;
    }

    public function setFactSheet(?bool $factSheet): static
    {
        $this->factSheet = $factSheet;

        return $this;
    }

    public function isOnemValidatedContract(): ?bool
    {
        return $this->onemValidatedContract;
    }

    public function setOnemValidatedContract(?bool $onemValidatedContract): static
    {
        $this->onemValidatedContract = $onemValidatedContract;

        return $this;
    }

    public function isBirthCertificate(): ?bool
    {
        return $this->birthCertificate;
    }

    public function setBirthCertificate(?bool $birthCertificate): static
    {
        $this->birthCertificate = $birthCertificate;

        return $this;
    }

    public function isMarriageLicense(): ?bool
    {
        return $this->marriageLicense;
    }

    public function setMarriageLicense(?bool $marriageLicense): static
    {
        $this->marriageLicense = $marriageLicense;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getFunctionTitle(): ?FunctionTitle
    {
        return $this->functionTitle;
    }

    public function setFunctionTitle(?FunctionTitle $functionTitle): static
    {
        $this->functionTitle = $functionTitle;

        return $this;
    }

    public function getAffectedLocation(): ?AffectedLocation
    {
        return $this->affectedLocation;
    }

    public function setAffectedLocation(?AffectedLocation $affectedLocation): static
    {
        $this->affectedLocation = $affectedLocation;

        return $this;
    }

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): static
    {
        $this->division = $division;

        return $this;
    }
}
