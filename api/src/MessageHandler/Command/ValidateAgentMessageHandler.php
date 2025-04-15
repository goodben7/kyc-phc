<?php

namespace App\MessageHandler\Command;

use App\Entity\Agent;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Command\ValidateAgentMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ValidateAgentMessageHandler 
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $em, 
    )
    {
    }

    public function __invoke(ValidateAgentMessage $event)
    {
        $agent = $event->agent;

        if ($agent->getStatus() === Agent::STATUS_VALIDATE) {
            $this->logger->warning('the agent %s is already validated : ' .$agent->getFullName());
            return;
        }

        if (null === $agent->getSite()) {
            $this->logger->warning('cannot find site with agent: %s ' .$agent->getFullName());
            return;
        }

        $agent->setStatus(agent::STATUS_VALIDATE);
        $agent->setUpdatedAt(new \DateTimeImmutable('now'));
        $agent->setValidatedAt(new \DateTimeImmutable('now'));
        $agent->setValidatedBy($event->userId);
        
        $agent->setIdentificationNumber($event->identificationNumber);

        $this->em->persist($agent);
        $this->em->flush();
    }
}