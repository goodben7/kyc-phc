<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\State\StatSiteProvider;
use Symfony\Component\Uid\Uuid;
use App\State\StatFunctionProvider;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\State\StatAgentsBysiteProvider;
use App\State\StatAgentsByStatusProvider;
use App\State\StatAgentsByGenderProcessor;
use App\State\StatAgentsByDivisionProvider;
use App\State\StatAgentsByCategoryProcessor;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    shortName: "StatResource",
    operations: [
        new Get(
            uriTemplate: '/stats/agents/sites',
            normalizationContext: ['groups' => 'stat:get'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            provider: StatAgentsBysiteProvider::class
        ),
        new Get(
            uriTemplate: '/stats/agents/status',
            normalizationContext: ['groups' => 'stat:get'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            provider: StatAgentsByStatusProvider::class
        ),
        new Get(
            uriTemplate: '/stats/agents/divisions',
            normalizationContext: ['groups' => 'stat:get'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            provider: StatAgentsByDivisionProvider::class
        ),
        new Get(
            uriTemplate: '/stats/functions',
            normalizationContext: ['groups' => 'stat:get'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            provider: StatFunctionProvider::class
        ),
        new Get(
            uriTemplate: '/stats/sites',
            normalizationContext: ['groups' => 'stat:get'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            provider: StatSiteProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => 'stat:get'],
            denormalizationContext: ['groups' => 'stat:post'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            uriTemplate: '/stats/agents/categories',
            processor: StatAgentsByCategoryProcessor::class,
            status: 200
        ),
        new Post(
            normalizationContext: ['groups' => 'stat:get'],
            denormalizationContext: ['groups' => 'stat:post'],
            security: 'is_granted("ROLE_STAT_AGENT")',
            uriTemplate: '/stats/agents/genders',
            processor: StatAgentsByGenderProcessor::class,
            status: 200
        )
    ]
)]
class StatResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Groups(['stat:get'])]
        public ?string $id = null,

        /** @var \App\Model\StatResourceModel[] $datasets */
        #[Groups(['stat:get'])]
        public array $datasets = [],

        #[Groups(['stat:post'])]
        public ?string $siteId = null
    )
    {
        $this->id = $id ?: Uuid::v4()->toRfc4122();
    }
}