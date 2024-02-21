<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

//    /**
//     * @return Company[] Returns an array of Company objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Company
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getInvoices($company_id): ?array
    {
        return $this->createQueryBuilder('c')
            ->select('u.firstname', 'u.lastname', 'u.email', 'i.id', 'i.creationDate', 'i.paymentStatus', 'i.totalHT', 'i.totalTTC')
            ->innerJoin('c.users', 'u') 
            ->innerJoin('u.invoices', 'i')
            ->where('c.id = :company_id')
            ->setParameter('company_id', $company_id)
            ->getQuery()
            ->getResult();
    }

    public function getQuotations($company_id): ?array
    {
        return $this->createQueryBuilder('c')
            ->select('u.firstname', 'u.lastname', 'u.email', 'q.id', 'q.creationDate', 'q.status', 'q.totalHT', 'q.totalTTC')
            ->innerJoin('c.users', 'u')
            ->innerJoin('u.quotations', 'q')
            ->where('c.id = :company_id')
            ->setParameter('company_id', $company_id)
            ->getQuery()
            ->getResult();
    }

    public function getProducts($company_id): ?array
    {
        return $this->createQueryBuilder('c')
            ->select('p.id', 'p.productName', 'p.description', 'p.unitPrice')
            ->innerJoin('c.products', 'p')
            ->where('c.id = :company_id')
            ->setParameter('company_id', $company_id)
            ->getQuery()
            ->getResult();
    }
}
