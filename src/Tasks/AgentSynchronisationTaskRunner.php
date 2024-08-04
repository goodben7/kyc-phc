<?php

namespace App\Tasks;

use App\Entity\Task;
use App\Model\NewAgentModel;
use App\Model\TaskInterface;
use Psr\Log\LoggerInterface;
use App\Manager\AgentManager;
use App\Model\TaskRunnerInterface;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\InvalidActionInputException;

class AgentSynchronisationTaskRunner  implements TaskRunnerInterface
{
    const SUPPORT_TYPE = "AGENT";

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface        $logger,
        private readonly AgentManager        $manager,
        private readonly TaskRepository $repository,
        private readonly ManagerRegistry $managerRegistry,
    )
    {
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
            elseif($task->getMethod() === Task::METHOD_UPDATE){
                $this->logger->info('Starting to process record: ' . json_encode($task->getData()));
    
                $this->update($task);
    
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
        try {
    
            if ($task->getDataValue('createdAt') !== null) {
                $createdAt = \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('createdAt'));
            }

            $model = new NewAgentModel();
            $model->firstName = $task->getDataValue('firstName');
            $model->lastName = $task->getDataValue('lastName');
            $model->postName = $task->getDataValue('postName');
            $model->country = $task->getDataValue('country');
            $model->birthday = $task->getDataValue('birthday');
            $model->maritalStatus = $task->getDataValue('maritalStatus');
            $model->gender = $task->getDataValue('gender');
            $model->createdAt = $createdAt;
            $model->createdBy = $task->getDataValue('createdBy');
            $model->address = $task->getDataValue('address');
            $model->address2 = $task->getDataValue('address2');
            $model->contact = $task->getDataValue('contact');
            $model->contact2 = $task->getDataValue('contact2');
            $model->externalReferenceId = $task->getDataValue('externalReferenceId');
            $model->oldIdentificationNumber = $task->getDataValue('oldIdentificationNumber');

            $this->managerRegistry->resetManager();
            $this->manager->createAgent($model);

            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_TERMINATED, null);
    
        } catch (\Exception $e) {
            $this->managerRegistry->resetManager();
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_FAILED, $e->getMessage());
            $this->logger->error('Error processing record: ' . json_encode($task->getData()) . ' - ' . $e->getMessage());
        }
    }

    private function update(TaskInterface $task): void
    {
        try {

            $agent = $this->manager->findByExternalReferenceId($task->getDataValue('externalReferenceId'));
    
            if ($task->getDataValue('updatedAt') !== null) {
                $updatedAt = \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('updatedAt'));
            }

            if ($task->getDataValue('birthday') !== null) {
                $birthday = \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('birthday'));
            }

            $agent->setFirstName($task->getDataValue('firstName'));
            $agent->setLastName($task->getDataValue('lastName'));
            $agent->setPostName($task->getDataValue('postName'));
            $agent->setCountry($task->getDataValue('country'));
            $agent->setBirthday($birthday);
            $agent->setMaritalStatus($task->getDataValue('maritalStatus'));
            $agent->setGender($task->getDataValue('gender'));
            $agent->setUpdatedBy($task->getDataValue('updatedBy'));
            $agent->setUpdatedAt($updatedAt);
            $agent->setAddress($task->getDataValue('address'));
            $agent->setAddress2($task->getDataValue('address2'));
            $agent->setContact($task->getDataValue('contatc'));
            $agent->setContatc2($task->getDataValue('contatc2'));
            $agent->setOldIdentificationNumber($task->getDataValue('oldIdentificationNumber'));
    
            $this->manager->update($agent);
    
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_TERMINATED, null);

        } catch (\Exception $e) {
            $this->managerRegistry->resetManager();
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_FAILED, $e->getMessage());
            $this->logger->error('Error processing record: ' . json_encode($task->getData()) . ' - ' . $e->getMessage());
        }

    }

}
