<?php
namespace App\ApiResource;

use ApiPlatform\Metadata\Post;
use Symfony\Component\Uid\Uuid;
use App\Dto\ExportAgentResultDto;
use App\State\ExportAgentProcessor;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: "ExportAgent",
    operations: [
        new Post(
            normalizationContext: ['groups' => 'export_agent:get'],
            denormalizationContext: ['groups' => 'export_agent:post'],
            security: 'is_granted("ROLE_AGENT_EXPORT")',
            output: ExportAgentResultDto::class,
            processor: ExportAgentProcessor::class,
            status: 200
        )
    ]
)]
class ExportAgent
{
    public function __construct(
        #[Groups(['export_agent:get'])]
        #[ApiProperty(identifier: true)]
        public ?Uuid $id = null,

        #[Groups(['export_agent:get'])]
        public array $datasets = []
    )
    {
    }

}