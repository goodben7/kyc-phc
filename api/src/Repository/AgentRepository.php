<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Agent>
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Agent::class);
    }

    public function findByExternalReferenceId(string $externalReferenceId): ?Agent
    {
        return $this->em->createQueryBuilder()
            ->select('a')
            ->from(Agent::class, 'a')
            ->where('a.externalReferenceId = :externalReferenceId')
            ->setParameter('externalReferenceId', $externalReferenceId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param array $filters
     * [
     *     'site' => 'nom_site',
     *     'category' => 'nom_catégorie',
     *     'functionTitle' => 'nom_fonction',
     *     'affectedLocation' => 'nom_lieu_affectation'
     *     'division' => 'division'
     * ]
     * 
     * @return array
     */
    public function findAgents(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select([
                'a.identificationNumber AS identificationNumber',
                'a.lastName AS lastName',
                'a.postName AS postName',
                'a.firstName AS firstName',
                'c.label AS category',
                'f.label AS functionTitle',
                'al.label AS affectedLocation',
                'd.label AS division',
                's.label AS site',
                'a.oldIdentificationNumber AS oldIdentificationNumber',
                'a.country AS country',
                'a.placeBirth AS placeBirth',
                'a.birthday AS birthday',
                'a.kycStatus AS kycStatus',
                'a.maritalStatus AS maritalStatus',
                'a.gender AS gender',
                'a.status AS status',
                'a.contact AS contact',
                'a.contact2 AS contact2',
                'a.address AS address',
                'a.address2 AS address2',
                'a.contractualNetPayUsd AS contractualNetPayUsd',
                'a.contractualNetPayCdf AS contractualNetPayCdf',
                'a.dateHire AS dateHire',
                'a.contratType AS contratType',
                'a.endContractDate AS endContractDate',
                'a.annotation AS annotation',
                'a.socialSecurityId AS socialSecurityId',
                'a.taxIdNumber AS taxIdNumber',
                'a.bankAccountId AS bankAccountId',
                'a.dependent AS dependent',
                'a.emergencyContactPerson AS emergencyContactPerson',
                'a.factSheet AS factSheet',
                'a.onemValidatedContract AS onemValidatedContract',
                'a.birthCertificate AS birthCertificate',
                'a.marriageLicense AS marriageLicense'
            ])
            ->leftJoin('a.site', 's')
            ->leftJoin('a.category', 'c')
            ->leftJoin('a.functionTitle', 'f')
            ->leftJoin('a.affectedLocation', 'al')
            ->leftJoin('a.division', 'd');

        if (!empty($filters['site'])) {
            $qb->andWhere('s.id = :site')
            ->setParameter('site', $filters['site']);
        }

        if (!empty($filters['category'])) {
            $qb->andWhere('c.id = :category')
            ->setParameter('category', $filters['category']);
        }

        if (!empty($filters['functionTitle'])) {
            $qb->andWhere('f.id = :functionTitle')
            ->setParameter('functionTitle', $filters['functionTitle']);
        }

        if (!empty($filters['affectedLocation'])) {
            $qb->andWhere('al.id = :affectedLocation')
            ->setParameter('affectedLocation', $filters['affectedLocation']);
        }

        if (!empty($filters['division'])) {
            $qb->andWhere('d.id = :division')
            ->setParameter('division', $filters['division']);
        }

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function findLastAgent(): ?Agent
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')  
            ->setMaxResults(1)       
            ->getQuery()
            ->getOneOrNullResult()
        ;  
    }

    public function countAgentsWithIdentificationNumber(): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.identificationNumber IS NOT NULL')  
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countAgentsWithIdentificationNumberBySite(Site $site): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.identificationNumber IS NOT NULL')
            ->andWhere('a.site = :site') 
            ->setParameter('site', $site)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAgentsBysite(): array
    {
        $result = $this->createQueryBuilder('a')
            ->select('s.label AS site, COUNT(a.id) AS count')
            ->leftJoin('a.site', 's')
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        if (empty($result))
            return ['total' => 0];

        $data = array_column($result, 'count', 'site');
        $data['total'] = array_sum($data);

        return $data;
    }

    public function countAgentsByStatus(): array
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.status AS status, COUNT(a.id) AS count')
            ->groupBy('a.status')
            ->getQuery()
            ->getResult();

        if (empty($result))
            return ['total' => 0];

        $data = array_column($result, 'count', 'status');
        $data['total'] = array_sum($data);

        return $data;
    }

    public function countAgentsByDivision(): array
    {
        $result = $this->createQueryBuilder('a')
            ->select('d.label AS division, COUNT(a.id) AS count')
            ->leftJoin('a.division','d')
            ->groupBy('d.id')
            ->getQuery()
            ->getResult();

        if (empty($result))
            return ['total' => 0];

        $data = array_column($result, 'count','division');
        $data['total'] = array_sum($data);

        return $data;
    }

    public function countAgentsByCategory(?Site $site): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('c.label AS category, COUNT(a.id) AS count')
            ->leftJoin('a.category', 'c');

        if ($site) {
            $qb->where('a.site = :site')
            ->setParameter('site', $site);
        }

        $qb->groupBy('c.id');

        $result = $qb->getQuery()->getResult();

        if (empty($result))
            return ['total' => 0];

        $data = array_column($result, 'count','category');
        $data['total'] = array_sum($data);

        return $data;
    }

    public function countAgentsByGender(?Site $site): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.gender AS gender, COUNT(a.id) AS count')
        ;

        if ($site) {
            $qb->where('a.site = :site')
            ->setParameter('site', $site);
        }
        
        $qb->groupBy('a.gender');
        $result = $qb->getQuery()->getResult();

        if (empty($result))
            return ['total' => 0];

        $data = array_column($result, 'count','gender');
        $data['total'] = array_sum($data);
        
        return $data;
    }


//    /**
//     * @return Agent[] Returns an array of Agent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Agent
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
