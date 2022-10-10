<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Entity\Autres;
use App\Entity\Chat;
use App\Entity\Chien;
use App\Entity\Reptile;
use App\Entity\User;
use App\Entity\Volatile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    private $objAnimal;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
        $this->objAnimal = ["chat" => Chat::class, "chien" => Chien::class, "volatile" => Volatile::class, "reptile" => Reptile::class, "autres" => Autres::class];
        $this->objAnimalNameSpace = [
            "chat" => "App\Entity\Chat",
            "chien" => "App\Entity\Chien",
            "volatile" => "App\Entity\Volatile",
            "reptile" => "App\Entity\Reptile",
            "autres" => "App\Entity\Autres",
        ];
    }

    public function animalListFilter($animalSpecies, $animalSex, $animalDepartment, $animalOrder)
    {
        $query = $this->createQueryBuilder('l');
        if($animalSpecies){
            $query
                  ->select('l')
                  ->andWhere("l.species IN (:ids)")
                  ->setParameter("ids", $animalSpecies);
        }
       if($animalSex){
            $query->andWhere("l.sex = ?0")
                ->setParameter(0, $animalSex);
        };
        if($animalDepartment){
            $query->andWhere("l.department = ?1")
                ->setParameter(1, $animalDepartment);
        };
        if($animalOrder){
            if($animalOrder==="plus recent") $query->select('l')->orderBy('l.createdAt', 'DESC');;
            if($animalOrder==="plus ancient") $query->select('l')->orderBy('l.createdAt', 'ASC');
        } else {
            $query->select('l')->orderBy('l.createdAt', 'DESC');
        }
        $query->getQuery();
        return $query;
    }

    public function findByUser(User $user)
    {
        $query = $this->createQueryBuilder('l');
        if($user){
            $query->andWhere("l.User = ?0")
                ->setParameter(0, $user);
        };
        return $query->getQuery()->getResult();
    }

    public function findParticularSpecie(String $animalSpecie)
    {
        $query = $this->createQueryBuilder('l');
        if($animalSpecie){
            $query->select($animalSpecie)
                ->from($this->objAnimal[$animalSpecie], $animalSpecie);
        };
        return $query->getQuery()->getResult();
    }

    public function findRandomAnimals(Int $numberOfResult)
    {
        $query = $this->createQueryBuilder('l');
        $query
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand');
        return $query->getQuery()->setMaxResults($numberOfResult)->getResult();
    }

    public function findRandomAnimalForAnSpecie(String $animalSpecie, Int $numberOfResult)
    {
        $query = $this->createQueryBuilder('l');
        if($animalSpecie){
            $query->andWhere("l.species = ?0")
                ->setParameter(0, $animalSpecie)
                ->addSelect('RAND() as HIDDEN rand')
                ->orderBy('rand');
        };
        return $query->getQuery()->setMaxResults($numberOfResult)->getResult();
    }

    public function test()
    {
        $query = $this->createQueryBuilder('l');
        return $query->getQuery()->getResult();
    }

}
