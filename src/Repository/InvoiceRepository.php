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
            ->join('i.userReference', 'u') // Assurez-vous que 'userReference' est le bon nom de la propriété dans Invoice qui référence User
            ->where('u.company = :companyId') // 'company' doit être la propriété dans User qui référence Company
            ->setParameter('companyId', $companyId)
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
