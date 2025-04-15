<?php

namespace App\MessageHandler\Query;

use App\Entity\Agent;
use App\Repository\AgentRepository;
use App\Message\Query\QueryHandlerInterface;
use App\Message\Query\GetAgentDetailsByExternalReferenceId;

class GetAgentDetailsByExternalReferenceIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(private AgentRepository $repository)
    {
    }

    public function __invoke(GetAgentDetailsByExternalReferenceId $query): ?Agent
    {
        /** @var Agent|null $agent */
        $agent = $this->repository->findByExternalReferenceId($query->externalReferenceId);

        if (null === $agent) {
            return null;
        }

        return $agent;
    }
}
