<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Agent;
use App\Model\NewAgentModel;
use App\Message\Query\GetUserDetails;
use App\Message\Query\QueryBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\UnavailableDataException;
use Symfony\Bundle\SecurityBundle\Security;
use App\Exception\UnauthorizedActionException;

class AgentManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private QueryBusInterface $queries,
    )
    {
    }

    public function createAgent(NewAgentModel $model): Agent 
    {

        $userId = $this->security->getUser()->getUserIdentifier();

        /** @var User $user */
        $user = $this->queries->ask(new GetUserDetails($userId));

        $a = new Agent();
        $a->setFirstName($model->firstName);
        $a->setLastName($model->lastName);
        $a->setPostName($model->postName);
        $a->setBirthday(new \DateTimeImmutable($model->birthday));
        $a->setCountry($model->country);
        $a->setMaritalStatus($model->maritalStatus);
        $a->setCreatedAt($model->createdAt ?? new \DateTimeImmutable('now'));
        $a->setCreatedBy($model->createdBy ?? $user->getId());
        $a->setStatus(Agent::STATUS_PENDING);
        $a->setKycStatus(Agent::KYC_STATUS_NOT_VERIFIED);
        $a->setGender($model->gender);
        $a->setExternalReferenceId($model->externalReferenceId);
        $a->setAddress($model->address);
        $a->setAddress2($model->address2);

        $this->em->persist($a);

        $a->setIdentificationNumber($a->getId());

        $this->em->persist($a);
        $this->em->flush();

        return $a;
    }

    private function findAgnet(string $agentId): Agent 
    {
        $agent = $this->em->find(Agent::class, $agentId);

        if (null === $agent) {
            throw new UnavailableDataException(sprintf('cannot find agent with id: %s', $agentId));
        }

        return $agent; 
    }

    public function delete(string $agentId) {
        $user = $this->findAgnet($agentId);

        if ($user->isDeleted()) {
            throw new UnauthorizedActionException('this action is not allowed');
        }

        $user->setDeleted(true);
        $user->setUpdatedAt(new \DateTimeImmutable('now'));

        $this->em->persist($user);
        $this->em->flush();
    }
}