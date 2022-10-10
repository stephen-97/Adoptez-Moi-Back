<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Animal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByAnimal(Animal $animal)
    {
        $query = $this->createQueryBuilder('l');
        if($animal){
            $query->andWhere("l.Animal = ?0")
                ->setParameter(0, $animal);
        };
        return $query->getQuery()->getResult();
    }


    public function findByUser(User $user)
    {
        $query = $this->createQueryBuilder('l');
        if($user){
            $query->andWhere("l.receiver = ?0")
                ->setParameter(0, $user)
                ->orderBy('l.id', 'DESC');
        };
        return $query->getQuery()->getResult();
    }
}
