<?php

namespace App\Repository;

use App\Entity\Import;
use App\Entity\ImportErrorItem;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Import>
 */
class ImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Import::class);
    }

    public function updateImportStatus(string $id, string $status, ?string $message): void
    {
        $query = $this->em->createQuery(sprintf('UPDATE %s t SET t.status = :status, t.message = :message WHERE t.id = :id', Import::class));
        $query->setParameter('id', $id);
        $query->setParameter('status', $status);
        $query->setParameter('message', $message);
        $query->execute();
    }

    public function updateImportloading(string $id, int $loading): void
    {
        $query = $this->em->createQuery(sprintf('UPDATE %s t SET t.loaded = :loaded WHERE t.id = :id', Import::class));
        $query->setParameter('id', $id);
        $query->setParameter('loaded', $loading);
        $query->execute();
    }

    public function addImportErrorItem(string $id, string $message, ?int $loading): void
    {
        $importErrorItem = new ImportErrorItem();
        $importErrorItem->setImportId($id);
        $importErrorItem->setMessage($message);
        $importErrorItem->setLoading($loading);
        $importErrorItem->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($importErrorItem);
        $this->em->flush();
    }


    //    /**
    //     * @return Import[] Returns an array of Import objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Import
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
