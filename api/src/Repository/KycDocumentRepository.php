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

    /**
     * @return array
     */
    public function findDocuments(): array
    {
        $qb = $this->createQueryBuilder('k');

        $qb->select([
            'k.id',
            'k.type',
            'k.documentRefNumber',
            'k.status',
            'k.filePath',
            'k.fileSize',
            'a.id AS agentId',  
            'a.fullName AS agentFullName',
            'a.identificationNumber AS identificationNumber'
        ])
        ->leftJoin('k.agent', 'a');

        return $qb->getQuery()->getArrayResult();
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
