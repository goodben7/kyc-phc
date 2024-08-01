<?php

namespace App\Repository;

use App\Entity\KycDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KycDocument>
 */
class KycDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KycDocument::class);
    }

    //    /**
    //     * @return KycDocument[] Returns an array of KycDocument objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('k')
    //            ->andWhere('k.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('k.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?KycDocument
    //    {
    //        return $this->createQueryBuilder('k')
    //            ->andWhere('k.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
