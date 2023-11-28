<?php

namespace App\Repository;

use App\Entity\QuotationDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuotationDetail>
 *
 * @method QuotationDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuotationDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuotationDetail[]    findAll()
 * @method QuotationDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuotationDetail::class);
    }

//    /**
//     * @return QuotationDetail[] Returns an array of QuotationDetail objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuotationDetail
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
