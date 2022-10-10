<?php

namespace App\Repository;

use App\Entity\Autres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Autres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autres[]    findAll()
 * @method Autres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autres::class);
    }

    // /**
    //  * @return Autres[] Returns an array of Autres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Autres
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
