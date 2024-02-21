<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function getInvoiceStatusCounts(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.paymentStatus AS paymentStatus, COUNT(i.id) AS statusCount')
            ->groupBy('i.paymentStatus');

        return $qb->getQuery()->getResult();
    }

    public function getProductSalesData()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.productName, SUM(d.quantity) as totalSales
            FROM App\Entity\Product p
            JOIN p.invoiceDetails d
            GROUP BY p.id'
        );

        return $query->getResult();
    }

    public function findTotalSalesByMonth()
    {
        // Assurez-vous d'utiliser le bon nom de champ totalTTC ici
        return $this->createQueryBuilder('i')
            ->select('SUBSTRING(i.creationDate, 1, 7) as month, SUM(i.totalTTC) as total')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Invoice[] Returns an array of Invoice objects
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

//    public function findOneBySomeField($value): ?Invoice
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
