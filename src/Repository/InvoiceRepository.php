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

    public function getInvoiceStatusCounts($companyId): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.paymentStatus AS paymentStatus, COUNT(i.id) AS statusCount')
            ->join('i.userReference', 'u')
            ->where('u.company = :companyId')
            ->setParameter('companyId', $companyId)
            ->groupBy('i.paymentStatus');

        return $qb->getQuery()->getResult();
    }

    public function getInvoiceStatusCountsGlobal(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.paymentStatus AS paymentStatus, COUNT(i.id) AS statusCount')
            ->groupBy('i.paymentStatus');

        return $qb->getQuery()->getResult();
    }

    public function findTotalSalesByMonth($companyId)
    {
        $result = $this->createQueryBuilder('i')
            ->select('i.creationDate, SUM(i.totalTTC) as total')
            ->join('i.userReference', 'u')
            ->where('u.company = :companyId')
            ->setParameter('companyId', $companyId)
            ->groupBy('i.creationDate')
            ->orderBy('i.creationDate', 'ASC')
            ->getQuery()
            ->getResult();

        $salesByMonth = [];
        foreach ($result as $row) {
            $month = $row['creationDate']->format('Y-m');
            if (!isset($salesByMonth[$month])) {
                $salesByMonth[$month] = 0;
            }
            $salesByMonth[$month] += $row['total'];
        }

        $formattedResult = [];
        foreach ($salesByMonth as $month => $total) {
            $formattedResult[] = ['month' => $month, 'total' => $total];
        }

        return $formattedResult;
    }

    public function findTotalSalesByMonthGlobal()
    {
        $result = $this->createQueryBuilder('i')
            ->select('i.creationDate, SUM(i.totalTTC) as total')
            ->groupBy('i.creationDate')
            ->orderBy('i.creationDate', 'ASC')
            ->getQuery()
            ->getResult();

        $salesByMonth = [];
        foreach ($result as $row) {
            $month = $row['creationDate']->format('Y-m');
            if (!isset($salesByMonth[$month])) {
                $salesByMonth[$month] = 0;
            }
            $salesByMonth[$month] += $row['total'];
        }

        $formattedResult = [];
        foreach ($salesByMonth as $month => $total) {
            $formattedResult[] = ['month' => $month, 'total' => $total];
        }

        return $formattedResult;
    }

    public function findInvoicesByCompany($companyId): array
    {
        return $this->createQueryBuilder('i')
            ->select('i.id', 'i.creationDate', 'i.paymentStatus', 'i.totalHT', 'i.totalTTC', 'u.firstname', 'u.lastname', 'u.email')
            ->innerJoin('i.userReference', 'u')
            ->innerJoin('u.company', 'c')
            ->where('c.id = :companyId')
            ->andWhere('i.paymentStatus = :paymentStatus')
            ->setParameter('companyId', $companyId)
            ->setParameter('paymentStatus', 'Payé')
            ->getQuery()
            ->getResult();
    }

    public function findInvoicesByCompanyAccountant($companyId): array
    {
        return $this->createQueryBuilder('i')
            ->select('i.id', 'i.creationDate', 'i.paymentStatus', 'i.totalHT', 'i.totalTTC', 'u.firstname', 'u.lastname', 'u.email')
            ->innerJoin('i.userReference', 'u')
            ->innerJoin('u.company', 'c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)
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
