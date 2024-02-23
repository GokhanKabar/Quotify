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
        return $this->createQueryBuilder('i')
            ->select('SUBSTRING(i.creationDate, 1, 7) as month, SUM(i.totalTTC) as total')
            ->join('i.userReference', 'u') // Assurez-vous que 'userReference' est le nom de la propriété dans `Invoice` qui référence `User`
            ->where('u.company = :companyId') // Filtrez pour correspondre à l'ID de la compagnie
            ->setParameter('companyId', $companyId)
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
