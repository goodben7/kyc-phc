<?php

namespace App\Repository;

use App\Entity\ImportErrorItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImportErrorItem>
 *
 * @method TaskErrorItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskErrorItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskErrorItem[]    findAll()
 * @method TaskErrorItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportErrorItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportErrorItem::class);
    }

    //    /**
    //     * @return TaskErrorItem[] Returns an array of TaskErrorItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TaskErrorItem
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
