<?php

namespace App\EventListener\Doctrine;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Agent;
use Doctrine\ORM\Events;
use App\Message\Query\GetUserDetails;
use App\Message\Query\QueryBusInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;

#[AsDoctrineListener(event: Events::postPersist)]
class TaskEventListener
{
    public function __construct(
        private readonly Security $security,
        private readonly QueryBusInterface $queries,
    )
    {
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $userId = $this->security->getUser()->getUserIdentifier();

        /** @var User $user */
        $user = $this->queries->ask(new GetUserDetails($userId));
        
        $entity = $args->getObject();

        if ($entity instanceof Agent) {
            $task = $args->getObjectManager()->getRepository(Task::class)->findOneBy(['externalReferenceId' => $entity->getExternalReferenceId()]);
            if ($task) {
                $task->setSynchronizedBy($user->getId());
                $task->setSynchronizedAt(new \DateTimeImmutable());
                $args->getObjectManager()->persist($task);
                $args->getObjectManager()->flush();
            }
            else{
                return null;
            }
        }
    }
}