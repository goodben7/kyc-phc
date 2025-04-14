<?php

namespace App\State;

use App\ApiResource\StatResource;
use App\Model\StatResourceModel;
use ApiPlatform\Metadata\Operation;
use App\Repository\AgentRepository;
use ApiPlatform\State\ProviderInterface;

class StatAgentsBysiteProvider  implements ProviderInterface
{
    public function __construct(
        private  AgentRepository $repository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $agentStats = $this->repository->countAgentsBysite();

        $agentModel = [];

        $agentModel = array_map(
            fn ($key, $value) => new StatResourceModel($key, $value),
            array_keys($agentStats),
            $agentStats
        );

        return new StatResource(
            null,
            $agentModel
        );
 
    }

}