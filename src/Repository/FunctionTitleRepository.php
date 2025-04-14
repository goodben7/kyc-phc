<?php

namespace App\Repository;

use App\Entity\FunctionTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FunctionTitle>
 */
class FunctionTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FunctionTitle::class);
    }

    public function countFunctionTitle(): array
    {
        $result = $this->createQueryBuilder('f')
            ->select('COUNT(f.id) as total')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return ['total' => $result];
    }
    

    //    /**
    //     * @return FunctionTitle[] Returns an array of FunctionTitle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FunctionTitle
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
