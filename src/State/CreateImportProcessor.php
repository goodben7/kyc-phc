<?php

namespace App\State;

use App\Entity\User;
use App\Entity\Import;
use ApiPlatform\Metadata\Operation;
use App\Message\Query\GetUserDetails;
use App\Model\TaskFileManagerInterface;
use App\Message\Query\QueryBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Message\Command\CommandBusInterface;

class CreateImportProcessor implements ProcessorInterface
{
    const DIRECTORY = 'import';

    public function __construct(
        private EntityManagerInterface $em,
        private CommandBusInterface $bus,
        private TaskFileManagerInterface $manager,
        private Security $security,
        private QueryBusInterface $queries,
    )
    {
    }

    /**
     * @param \App\Dto\CreateImportDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
 
        $userId = $this->security->getUser()->getUserIdentifier();

        /** @var User $user */
        $user = $this->queries->ask(new GetUserDetails($userId));


        $file = $this->manager->save($data->file, self::DIRECTORY);

        $import = new Import();

        $import->setType($data->type);
        $import->setMethod($data->method);
        $import->setMethod($data->method);
        $import->setDescription($data->description);
        $import->setStatus(Import::STATUS_IDLE);
        $import->setCreatedAt(new \DateTimeImmutable('now'));
        $import->setCreatedBy($user->getId());
        $import->setData1($this->manager->getPath($file, self::DIRECTORY));
        $import->setData2($file);
        $import->setData3($data->data3);
        $import->setData4($data->data4);
        $import->setData5($data->data5);
        $import->setData6($data->data6);

        $this->em->persist($import);
        $this->em->flush();

        //$this->bus->dispatch(new CheckPendingTasksCommand());
        
        return $import;
    }
}