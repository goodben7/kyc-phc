<?php
namespace App\Model;

use App\Entity\Site;
use App\Entity\Agent;
use App\Entity\Category;
use App\Entity\Division;
use App\Entity\FunctionTitle;
use App\Entity\AffectedLocation;
use Symfony\Component\Validator\Constraints as Assert;

class NewAgentModel {

    #[
        Assert\NotBlank(),
        Assert\NotNull(),
        Assert\Length(max: 30)
    ]
    public ?string $firstName = null;

    #[
        Assert\NotBlank(),
        Assert\NotNull(),
        Assert\Length(max: 30)
    ]
    public ?string $lastName = null;

    #[
        Assert\Length(max: 30)
    ]
    public ?string $postName = null;

    #[
        Assert\NotBlank(),
        Assert\NotNull(),
        Assert\Country(alpha3: false)
    ]
    public ?string $country = null;

    #[
        Assert\NotNull(),
        Assert\Date
    ]
    public ?string $birthday = null;

    #[
        Assert\NotNull(),
        Assert\Choice(callback: [Agent::class, "getMaritalStatusAsChoices"])
    ]
    public ?string $maritalStatus= "S";

    #[
        Assert\NotNull(),
        Assert\Choice(callback: [Agent::class, "getGenderAsChoices"])
    ]
    public ?string $gender= "M";

    public ?\DateTimeImmutable $createdAt = null;

    public ?string $address = null;

    public ?string $address2 = null;

    public ?string $createdBy = null;

    public ?string $externalReferenceId = null;
    public ?string $oldIdentificationNumber = null;

    public ?string $contact = null;

    public ?string $contact2 = null;

    public ?string $contractualNetPayUsd = null;

    public ?string $contractualNetPayCdf = null;

    public ?\DateTimeImmutable $dateHire = null;

    #[Assert\Choice(choices: [Agent::CONTRAT_TYPE_CDD, Agent::CONTRAT_TYPE_CDI])]
    public ?string $contratType = null;

    public ?\DateTimeImmutable $endContractDate = null;

    public ?string $annotation = null;

    public ?string $placeBirth = null;

    public ?string $socialSecurityId = null;

    public ?string $taxIdNumber = null;

    public ?string $bankAccountId = null;

    public ?int $dependent = null;

    public ?string $emergencyContactPerson = null;

    public ?bool $factSheet = null;

    public ?bool $onemValidatedContract = null;

    public ?bool $birthCertificate = null;

    public ?bool $marriageLicense = null;

    public ?Site $site = null;

    public ?Category $category = null;

    public ?FunctionTitle $functionTitle = null;

    public ?AffectedLocation $affectedLocation = null;

    public ?Division $division = null;
}