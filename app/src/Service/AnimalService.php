<?php

namespace App\Service;


use App\Entity\Animal;
use App\Repository\AnimalRepository;
use App\Entity\User;

class AnimalService {

    private $animalRepository;

    public function __construct(AnimalRepository $animalRepository)
    {
        $this->animalRepository = $animalRepository;
    }

    public function findAnimal($id){
        return $this->animalRepository->find($id);
    }

    public function getAllAnimalsForAnUser(User $user)
    {
        return $this->animalRepository->findByUser($user);
    }

    public function getCountForAnSpecie(String $specie)
    {
        return count($this->animalRepository->findParticularSpecie($specie));
    }

    public function getTopMostPopularDepartment(String $specie, Int $number)
    {
        $allAnimal = $this->animalRepository->findParticularSpecie($specie);
        $allDepartment = [];
        for($i=0; $i<count($allAnimal); $i++){
            if(!array_key_exists($allAnimal[$i]->getDepartment(), $allDepartment)){
                $allDepartment[$allAnimal[$i]->getDepartment()]=0;
            }
        }
        for($i=0; $i<count($allAnimal); $i++){
            if(array_key_exists($allAnimal[$i]->getDepartment(), $allDepartment)){
                $allDepartment[$allAnimal[$i]->getDepartment()]=$allDepartment[$allAnimal[$i]->getDepartment()]+1;
            }
        }
        $finalArray = [];
        $initialCountDepartment = count($allDepartment);
        for($i=0; $i<min($number, $initialCountDepartment); $i++){
            $value = max($allDepartment);
            $key = array_search($value, $allDepartment);
            $finalArray[$key] = $value;
            unset($allDepartment[$key]);
        }
        return $finalArray;
    }

    public function getRandomAnimal(String $species, Int $numberOfResult)
    {
        return $this->animalRepository->findRandomAnimalForAnSpecie($species, $numberOfResult);
    }

    public function getRandomAnimalWhateverTheSpecie(Int $numberOfResult)
    {
        return $this->animalRepository->findRandomAnimals($numberOfResult);
    }

    public function getAnimalListFiltered(String $species, Int $numberOfResult)
    {
        return $this->animalRepository->findRandomAnimalForAnSpecie($species, $numberOfResult);
    }

    public function serializeAnimal(Animal $animal){

    }
}