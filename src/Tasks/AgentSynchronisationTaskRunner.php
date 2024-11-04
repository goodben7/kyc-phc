<?php

namespace App\Tasks;

use App\Entity\Site;
use App\Entity\Task;
use App\Entity\Category;
use App\Model\NewAgentModel;
use App\Model\TaskInterface;
use Psr\Log\LoggerInterface;
use App\Entity\FunctionTitle;
use App\Manager\AgentManager;
use App\Entity\AffectedLocation;
use App\Entity\Division;
use App\Model\TaskRunnerInterface;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\UnavailableDataException;
use App\Exception\InvalidActionInputException;
use App\Repository\ImportRepository;

class AgentSynchronisationTaskRunner  implements TaskRunnerInterface
{
    const SUPPORT_TYPE = "AGENT";

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface        $logger,
        private readonly AgentManager        $manager,
        private readonly TaskRepository $repository,
        private readonly ImportRepository $importRepository,
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

            /** @var Site|null $site */
            $site = null;
            if (!is_null($task->getDataValue('site'))) {
                $site = $this->em->getRepository(Site::class)->find($task->getDataValue('site'));
                if (is_null($site))
                    throw new UnavailableDataException(sprintf("The site with ID %s doesn't exist", $task->getDataValue('site')));
            }

            /** @var Category|null $category */
            $category = null;
            if (!is_null($task->getDataValue('category'))) {
                $category = $this->em->getRepository(Category::class)->find($task->getDataValue('category'));
                if (is_null($category))
                    throw new UnavailableDataException(sprintf("The category with ID %s doesn't exist", $task->getDataValue('category')));
            }

            /** @var FunctionTitle|null $functionTitle */
            $functionTitle = null;
            if (!is_null($task->getDataValue('functionTitle'))) {
                $functionTitle = $this->em->getRepository(FunctionTitle::class)->find($task->getDataValue('functionTitle'));
                if (is_null($functionTitle))
                    throw new UnavailableDataException(sprintf("The function with ID %s doesn't exist", $task->getDataValue('functionTitle')));
            }

            /** @var AffectedLocation|null $affectedLocation */
            $affectedLocation = null;
            if (!is_null($task->getDataValue('affectedLocation'))) {
                $affectedLocation = $this->em->getRepository(AffectedLocation::class)->find($task->getDataValue('affectedLocation'));
                if (is_null($affectedLocation))
                    throw new UnavailableDataException(sprintf("The affected Location with ID %s doesn't exist", $task->getDataValue('affectedLocation')));
            }

            /** @var Division|null $division */
            $division = null;
            if (!is_null($task->getDataValue('division'))) {
                $division = $this->em->getRepository(Division::class)->find($task->getDataValue('division'));
                if (is_null($division))
                    throw new UnavailableDataException(sprintf("The Division with ID %s doesn't exist", $task->getDataValue('division')));
            }

            $model = new NewAgentModel();
            $model->firstName = $task->getDataValue('firstName');
            $model->lastName = $task->getDataValue('lastName');
            $model->postName = $task->getDataValue('postName');
            $model->country = $task->getDataValue('country');
            $model->birthday = $task->getDataValue('birthday');
            $model->maritalStatus = $task->getDataValue('maritalStatus');
            $model->gender = $task->getDataValue('gender');
            $model->createdAt = $task->getCreatedAt();
            $model->createdBy = $task->getCreatedBy();
            $model->address = $task->getDataValue('address');
            $model->address2 = $task->getDataValue('address2');
            $model->contact = $task->getDataValue('contact');
            $model->contact2 = $task->getDataValue('contact2');
            $model->externalReferenceId = $task->getDataValue('externalReferenceId');
            $model->oldIdentificationNumber = $task->getDataValue('oldIdentificationNumber');
            $model->contractualNetPayUsd = $task->getDataValue('contractualNetPayUsd');
            $model->contractualNetPayCdf = $task->getDataValue('contractualNetPayCdf');
            $model->dateHire = $task->getDataValue('dateHire') ? \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('dateHire')) : null;
            $model->contratType = $task->getDataValue('contratType');
            $model->endContractDate = $task->getDataValue('endContractDate') ? \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('endContractDate')) : null;
            $model->annotation = $task->getDataValue('annotation');
            $model->placeBirth = $task->getDataValue('placeBirth');
            $model->socialSecurityId = $task->getDataValue('socialSecurityId');
            $model->taxIdNumber = $task->getDataValue('taxIdNumber');
            $model->bankAccountId = $task->getDataValue('bankAccountId');
            $model->dependent = $task->getDataValue('dependent');
            $model->emergencyContactPerson = $task->getDataValue('emergencyContactPerson');
            $model->factSheet = $task->getDataValue('factSheet');
            $model->onemValidatedContract = $task->getDataValue('onemValidatedContract');
            $model->birthCertificate = $task->getDataValue('birthCertificate');
            $model->marriageLicense = $task->getDataValue('marriageLicense');
            $model->site = $site;
            $model->category = $category;
            $model->functionTitle = $functionTitle;
            $model->affectedLocation = $affectedLocation;
            $model->division = $division;


            $this->manager->createAgent($model);

            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_TERMINATED, null);
            
            if ($task->getData3() !== null) {
                $treated = $this->importRepository->getImportTreated($task->getData3());
                $this->importRepository->updateImportTreated($task->getData3(), ++$treated);
            }
    
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

            if ($task->getDataValue('birthday') !== null) {
                $birthday = \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('birthday'));
            }

            /** @var Site|null $site */
            $site = null;
            if (!is_null($task->getDataValue('site'))) {
                $site = $this->em->getRepository(Site::class)->find($task->getDataValue('site'));
                if (is_null($site))
                    throw new UnavailableDataException(sprintf("The site with ID %s doesn't exist", $task->getDataValue('site')));
            }

            /** @var Category|null $category */
            $category = null;
            if (!is_null($task->getDataValue('category'))) {
                $category = $this->em->getRepository(Category::class)->find($task->getDataValue('category'));
                if (is_null($category))
                    throw new UnavailableDataException(sprintf("The category with ID %s doesn't exist", $task->getDataValue('category')));
            }

            /** @var FunctionTitle|null $functionTitle */
            $functionTitle = null;
            if (!is_null($task->getDataValue('functionTitle'))) {
                $functionTitle = $this->em->getRepository(FunctionTitle::class)->find($task->getDataValue('functionTitle'));
                if (is_null($functionTitle))
                    throw new UnavailableDataException(sprintf("The function with ID %s doesn't exist", $task->getDataValue('functionTitle')));
            }

            /** @var AffectedLocation|null $affectedLocation */
            $affectedLocation = null;
            if (!is_null($task->getDataValue('affectedLocation'))) {
                $affectedLocation = $this->em->getRepository(AffectedLocation::class)->find($task->getDataValue('affectedLocation'));
                if (is_null($affectedLocation))
                    throw new UnavailableDataException(sprintf("The Affected Location with ID %s doesn't exist", $task->getDataValue('affectedLocation')));
            }

            /** @var Division|null $division */
            $division = null;
            if (!is_null($task->getDataValue('division'))) {
                $division = $this->em->getRepository(Division::class)->find($task->getDataValue('division'));
                if (is_null($division))
                    throw new UnavailableDataException(sprintf("The Division with ID %s doesn't exist", $task->getDataValue('division')));
            }

            $agent->setFirstName($task->getDataValue('firstName'));
            $agent->setLastName($task->getDataValue('lastName'));
            $agent->setPostName($task->getDataValue('postName'));
            $agent->setCountry($task->getDataValue('country'));
            $agent->setBirthday($birthday);
            $agent->setMaritalStatus($task->getDataValue('maritalStatus'));
            $agent->setGender($task->getDataValue('gender'));
            $agent->setUpdatedBy($task->getCreatedBy());
            $agent->setUpdatedAt($task->getCreatedAt());
            $agent->setAddress($task->getDataValue('address'));
            $agent->setAddress2($task->getDataValue('address2'));
            $agent->setContact($task->getDataValue('contatc'));
            $agent->setContact2($task->getDataValue('contatc2'));
            $agent->setOldIdentificationNumber($task->getDataValue('oldIdentificationNumber'));
            $agent->setContractualNetPayUsd($task->getDataValue('contractualNetPayUsd'));
            $agent->setContractualNetPayCdf($task->getDataValue('contractualNetPayCdf'));
            $agent->setDateHire($task->getDataValue('dateHire') ? \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('dateHire')) : null);
            $agent->setContratType($task->getDataValue('contratType'));
            $agent->setEndContractDate($task->getDataValue('endContractDate') ? \DateTimeImmutable::createFromFormat('Y-m-d', $task->getDataValue('endContractDate')) : null);
            $agent->setAnnotation($task->getDataValue('annotation'));
            $agent->setPlaceBirth($task->getDataValue('placeBirth'));
            $agent->setSocialSecurityId($task->getDataValue('socialSecurityId'));
            $agent->setTaxIdNumber($task->getDataValue('taxIdNumber'));
            $agent->setBankAccountId($task->getDataValue('bankAccountId'));
            $agent->setDependent($task->getDataValue('dependent'));
            $agent->setEmergencyContactPerson($task->getDataValue('emergencyContactPerson'));
            $agent->setFactSheet($task->getDataValue('factSheet'));
            $agent->setOnemValidatedContract($task->getDataValue('onemValidatedContract'));
            $agent->setBirthCertificate($task->getDataValue('birthCertificate'));
            $agent->setMarriageLicense($task->getDataValue('marriageLicense'));
            $agent->setSite($site);
            $agent->setCategory($category);
            $agent->setFunctionTitle($functionTitle);
            $agent->setAffectedLocation($affectedLocation);
            $agent->setDivision($division);
    
            $this->manager->update($agent);
    
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_TERMINATED, null);

        } catch (\Exception $e) {
            $this->managerRegistry->resetManager();
            $this->repository->updateTaskStatus($task->getId(), Task::STATUS_FAILED, $e->getMessage());
            $this->logger->error('Error processing record: ' . json_encode($task->getData()) . ' - ' . $e->getMessage());
        }

    }

}
