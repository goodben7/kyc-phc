<?php 

namespace App\MessageHandler\Command;

use App\Entity\Site;
use App\Entity\Task;
use App\Entity\Import;
use League\Csv\Reader;
use App\Entity\Category;
use App\Entity\Division;
use League\Csv\Statement;
use Psr\Log\LoggerInterface;
use App\Entity\FunctionTitle;
use App\Model\ImportInterface;
use Symfony\Component\Uid\Uuid;
use App\Entity\AffectedLocation;
use App\Repository\ImportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Message\Command\CreateTaskCommand;
use App\Exception\UnavailableDataException;
use App\Message\Command\CommandBusInterface;
use App\Message\Command\CommandHandlerInterface;
use App\Message\Command\CheckPendingTasksCommand;

class CreateTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ImportRepository $repository,
        private EntityManagerInterface $em,
        private ManagerRegistry $managerRegistry,
        private CommandBusInterface $bus
    )
    {
    }

    public function __invoke(CreateTaskCommand $event)
    {
        try {

            $path = $event->import->getData1();
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if ($extension !== 'csv') {
                $this->logger->warning(sprintf('%s is not a CSV file', $extension));
                $this->repository->updateImportStatus($event->import->getId(), Import::STATUS_FAILED, 'Invalid file extension, expected CSV');
                return;
            }

            $stream = fopen($path, 'r');
            $csv = Reader::createFromStream($stream);
            $csv->setDelimiter(',');
            $csv->setHeaderOffset(0);

            $stmt = Statement::create()->offset($event->import->getLoaded());
            $records = $stmt->process($csv);

            $loading = 0;
            foreach ($records as $record) {
                $this->processRecord($record, $loading, $event->import);
                $this->repository->updateImportloading($event->import->getId(), ++$loading);
            }

            fclose($stream);

            $this->repository->updateImportStatus($event->import->getId(), Import::STATUS_TERMINATED, null);

            $this->bus->dispatch(new CheckPendingTasksCommand());

            $this->logger->info(sprintf('Agent importation Task Runner with ID %s completed successfully', $event->import->getId()));
            $this->logger->info('Total records processed : ' . $loading);

        }catch (\Exception $e) {
            $this->managerRegistry->resetManager();
            $this->repository->updateImportStatus($event->import->getId(), Import::STATUS_FAILED, $e->getMessage());
            $this->logger->info(sprintf('Agent importation Task Runner with ID %s failed', $event->import->getId()));
            $this->logger->error($e->getMessage());
        }
    }

    private function processRecord($record, int $loading, ImportInterface $import): void
    {
        try{

            /** @var Site|null $site */
            $site = null;
            if (!empty($record['site'])) {
                $site = $this->em->getRepository(Site::class)->findOneBy(['code' => $record['site']]);
                if (is_null($site))
                    throw new UnavailableDataException(sprintf("The site with CODE %s doesn't exist", $record['site']));
            }

            /** @var Category|null $category */
            $category = null;
            if (!empty($record['category'])) {
                $category = $this->em->getRepository(Category::class)->findOneBy(['code' => $record['category']]);
                if (is_null($category))
                    throw new UnavailableDataException(sprintf("The category with CODE %s doesn't exist", $record['category']));
            }

            /** @var FunctionTitle|null $functionTitle */
            $functionTitle = null;
            if (!empty($record['functionTitle'])) {
                $functionTitle = $this->em->getRepository(FunctionTitle::class)->findOneBy(['code' => $record['functionTitle']]);
                if (is_null($functionTitle))
                    throw new UnavailableDataException(sprintf("The function with CODE %s doesn't exist", $record['functionTitle']));
            }

            /** @var AffectedLocation|null $affectedLocation */
            $affectedLocation = null;
            if (!empty($record['affectedLocation'])) {
                $affectedLocation = $this->em->getRepository(AffectedLocation::class)->findOneBy(['code' => $record['affectedLocation']]);
                if (is_null($affectedLocation))
                    throw new UnavailableDataException(sprintf("The affected Location with CODE %s doesn't exist", $record['affectedLocation']));
            }

            /** @var Division|null $division */
            $division = null;
            if (!empty($record['division'])) {
                $division = $this->em->getRepository(Division::class)->findOneBy(['code' => $record['division']]);
                if (is_null($division))
                    throw new UnavailableDataException(sprintf("The division with CODE %s doesn't exist", $record['division']));
            }

            $externalReferenceId = Uuid::v1()->toString();

            $task = new Task();

            $task->setType($import->getType());
            $task->setMethod($import->getMethod());
            $task->setData($this->createJsonFromRecord($record, $site, $category, $functionTitle, $affectedLocation, $division, $externalReferenceId));
            $task->setCreatedBy('SYSTEM');
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setExternalReferenceId($externalReferenceId);
            $task->setStatus(Import::STATUS_IDLE);
            $task->setData3($import->getData3());
            $task->setData4($import->getData4());
            $task->setData5($import->getData5());
            $task->setData6($import->getData6());

            $this->em->persist($task);
            $this->em->flush();

        }catch (\Exception $e) {
            $this->managerRegistry->resetManager();
            $this->repository->addImportErrorItem($import->getId(), $e->getMessage(), $loading);
            $this->logger->error('Error processing record: ' . json_encode($record) . ' - ' . $e->getMessage());
        }
    }

    private function createJsonFromRecord(array $record, ?Site $site, ?Category $category, ?FunctionTitle $functionTitle, ?AffectedLocation $affectedLocation, ?Division $division, string $externalReferenceId): array
    {

        $data = [
            'site'                    => $site?->getId(),
            'gender'                  => $record['gender'] ?? null,
            'address'                 => $record['address'] ?? null,
            'country'                 => $record['country'] ?? null,
            'birthday'                => $record['birthday'] ?? null,
            'category'                => $category?->getId(),
            'dateHire'                => $record['dateHire'] ?? null,
            'lastName'                => $record['lastName'] ?? null,
            'postName'                => $record['postName'] ?? null,
            'dependent'               => isset($record['dependent']) ? intval($record['dependent']) : null,
            'factSheet'               => isset($record['factSheet']) ? boolval($record['factSheet']) : null,
            'firstName'               => $record['firstName'] ?? null,
            'annotation'              => $record['annotation'] ?? null,
            'placeBirth'              => $record['placeBirth'] ?? null,
            'contratType'             => $record['contratType'] ?? null,
            'taxIdNumber'             => $record['taxIdNumber'] ?? null,
            'bankAccountId'           => $record['bankAccountId'] ?? null,
            'functionTitle'           => $functionTitle?->getId(),
            'maritalStatus'           => $record['maritalStatus'] ?? null,
            'endContractDate'         => $record['endContractDate'] ?? null,
            'marriageLicense'         => isset($record['marriageLicense']) ? boolval($record['marriageLicense']) : null,
            'affectedLocation'        => $affectedLocation?->getId(),
            'division'                => $division?->getId(),
            'birthCertificate'        => isset($record['birthCertificate']) ? boolval($record['birthCertificate']) : null,
            'socialSecurityId'        => $record['socialSecurityId'] ?? null,
            'externalReferenceId'     => $externalReferenceId,
            'contractualNetPayCdf'    => $record['contractualNetPayCdf'] ?? null,
            'contractualNetPayUsd'    => $record['contractualNetPayUsd'] ?? null,
            'onemValidatedContract'   => isset($record['onemValidatedContract']) ? boolval($record['onemValidatedContract']) : null,
            'emergencyContactPerson'  => $record['emergencyContactPerson'] ?? null,
        ];

        return $data;
    }
}