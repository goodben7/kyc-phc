<?php
namespace App\ApiResource;

use ApiPlatform\Metadata\Post;
use Symfony\Component\Uid\Uuid;
use App\State\ExecuteTasksProcessor;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: "ExecuteTask",
    operations: [
        new Post(
            normalizationContext: ['groups' => 'execute_task:get'],
            denormalizationContext: ['groups' => 'execute_task:execute'],
            security: 'is_granted("ROLE_TASK_EXECUTE")',
            processor: ExecuteTasksProcessor::class,
            status: 200
        )
    ]
)]
class ExecuteTask {

    public function __construct(
        #[Groups(['execute_task:get'])]
        #[ApiProperty(identifier: true)]
        public ?Uuid $id = null,

        #[Groups(['execute_task:get'])]
        public ?bool $executed = false,
    )
    {
    }
}