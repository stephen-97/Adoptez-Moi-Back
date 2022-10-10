<?php

namespace App\Repository;

use App\Entity\Volatile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Volatile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Volatile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Volatile[]    findAll()
 * @method Volatile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolatileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volatile::class);
    }

    // /**
    //  * @return Volatile[] Returns an array of Volatile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Volatile
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
