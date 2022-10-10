<?php

namespace App\Repository;

use App\Entity\Reptile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reptile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reptile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reptile[]    findAll()
 * @method Reptile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReptileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reptile::class);
    }

    // /**
    //  * @return Reptile[] Returns an array of Reptile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reptile
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
