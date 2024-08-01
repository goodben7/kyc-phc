<?php
namespace App\Model;

use App\Entity\Agent;
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
}