<?php

namespace App\Repository;

use App\Entity\InvoiceDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvoiceDetail>
 *
 * @method InvoiceDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceDetail[]    findAll()
 * @method InvoiceDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceDetail::class);
    }

    public function getSalesData()
    {
        return $this->createQueryBuilder('id')
            ->select('p.productName, SUM(id.quantity) as totalSales')
            ->join('id.product', 'p')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return InvoiceDetail[] Returns an array of InvoiceDetail objects
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

//    public function findOneBySomeField($value): ?InvoiceDetail
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
