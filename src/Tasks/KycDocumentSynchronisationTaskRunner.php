<?php

namespace App\Tasks;

use App\Entity\Task;
use App\Entity\Agent;
use App\Entity\KycDocument;
use App\Model\TaskInterface;
use Psr\Log\LoggerInterface;
use App\Model\TaskRunnerInterface;
use App\Repository\TaskRepository;
use App\Model\TaskFileManagerInterface;
use App\Message\Query\QueryBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\UnavailableDataException;
use App\Exception\InvalidActionInputException;
use App\Message\Query\GetAgentDetailsByExternalReferenceId;

class KycDocumentSynchronisationTaskRunner  implements TaskRunnerInterface
{
    /** @var \App\Model\TaskFileManagerInterface $manager */
    private $manager;

    const SUPPORT_TYPE = "DOCUMENT";
    const DIRECTORY = 'media';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface        $logger,
        private readonly TaskRepository $repository,
        private readonly ManagerRegistry $managerRegistry,
        TaskFileManagerInterface $manager,
        private readonly QueryBusInterface $queries,
    )
    {
        $this->manager = $manager;
    }

    function support(string $type): bool
    {
        return $type === self::SUPPORT_TYPE;
    }

    function run(TaskInterface $task): void
    {
        try{

            if($task->getMethod() === Task::METHOD_CREATE){
                $this->logger->info('Starting to process record: ' . json_encode($task->getData()));
    
                $this->create($task);
    
                $this->logger->info('Processing record successfully: ' . json_encode($task->getData()));
            }
            else{
                $this->logger->warning("no runner found to handle task {$task->getId()} of method {$task->getMethod()}");
                throw new InvalidActionInputException("no runner found to handle task {$task->getId()} of method {$task->getMethod()}");
            }

        }catch(\Exception $e){
            $this->managerRegistry->resetManager();
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_FAILED, $e->getMessage());
            $this->logger->info(sprintf('Agent Synchronisation Task Runner with ID %s failed', $task->getId()));
            $this->logger->error($e->getMessage());
        } 
    } 

    private function create(TaskInterface $task): void
    {
        try{

            /** @var Agent $agent */
            $agent = $this->queries->ask(new GetAgentDetailsByExternalReferenceId($task->getData3()));

            if (null === $agent){
                throw new UnavailableDataException(sprintf("The agent with external Reference Id %s doesn't", $task->getData3()));   
            }

            $kycDocument = new KycDocument();
            $kycDocument->setType($task->getData1());
            $kycDocument->setDocumentRefNumber($task->getData2());
            $kycDocument->setUploadedAt(new \DateTimeImmutable());
            $kycDocument->setFilePath($task->getData4());
            $kycDocument->setContentUrl($this->manager->getPath($task->getData4(), self::DIRECTORY));
            $kycDocument->setFileSize($this->manager->getSize($task->getData4(), self::DIRECTORY));
            $kycDocument->setAgent($agent);
            $kycDocument->setExternalReferenceId($task->getExternalReferenceId());

            $this->em->persist($kycDocument);
            $this->em->flush();

            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_TERMINATED, null);

        }catch(\Exception $e){
            $this->managerRegistry->resetManager();
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_FAILED, $e->getMessage());
            $this->logger->error('Error processing record: ' . json_encode($task->getData()) . ' - ' . $e->getMessage());
        }
        
    }

}
