<?php
namespace App\EventListener\Doctrine;

use Doctrine\ORM\Events;
use App\Entity\Agent;
use App\Entity\KycDocument;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]
class AgentKycDocumentListener  {

    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        /** @var KycDocument $document */
        $document = $args->getObject();

        if (!$document instanceof KycDocument) {
            return;
        }

        $this->handleCustomerKYCStatus($document->getAgent());
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        /** @var KycDocument $document */
        $document = $args->getObject();

        if (!$document instanceof KycDocument) {
            return;
        }

        $this->handleCustomerKYCStatus($document->getAgent());
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        /** @var KycDocument $document */
        $document = $args->getObject();

        if (!$document instanceof KycDocument) {
            return;
        }

        $this->handleCustomerKYCStatus($document->getAgent());
    }

    private function handleCustomerKYCStatus(Agent $agent): void {
        $documents = $agent->getKycDocuments();

        $verified = 0;
        $pending = 0;
        $refused = 0;
        $count = 0;

        foreach ($documents as $doc) {
            if (KycDocument::STATUS_PENDING === $doc->getStatus()) {
                $pending++;
            }

            if (KycDocument::STATUS_REFUSED === $doc->getStatus()) {
                $refused++;
            }

            if (KycDocument::STATUS_VERIFIED === $doc->getStatus()) {
                $verified++;
            }

            $count++;
        }

        if ($count === $verified) {
            $agent->setKycStatus(Agent::KYC_STATUS_VERIFIED);
        }
        elseif ($count === $pending) {
            $agent->setKycStatus(Agent::KYC_STATUS_NOT_VERIFIED);
        }
        else {
            $agent->setKycStatus(Agent::KYC_STATUS_IN_PROGRESS);
        }

        $this->em->persist($agent);
        $this->em->flush();
    }
}