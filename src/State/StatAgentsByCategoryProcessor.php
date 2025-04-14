<?php

namespace App\State;

use App\Model\StatResourceModel;
use App\ApiResource\StatResource;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\Operation;
use App\Repository\AgentRepository;
use ApiPlatform\State\ProcessorInterface;
use App\Exception\UnavailableDataException;

class StatAgentsByCategoryProcessor  implements  ProcessorInterface
{
    public function __construct(
        private  AgentRepository $agentRepository,
        private SiteRepository $siteRepository
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): StatResource
    {
        if (empty($data->siteId)) {
            $agentStats = $this->agentRepository->countAgentsByCategory(null);
        } else {
            $site = $this->siteRepository->findOneBy(['id' => $data->siteId]);

            if (null === $site) {
                throw new UnavailableDataException(sprintf('cannot find site with id: %s', $data->siteId));
            }

            $agentStats = $this->agentRepository->countAgentsByCategory($site);
        }

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