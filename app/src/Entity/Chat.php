<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat extends Animal
{

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $chipOrTatoo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $identificationNumber;


    /**
     * @param $chipOrTatoo
     * @param $identificationNumber
     */
    public function __construct(String $name, String $species, $race, Bool $vaccinated,  String $sex, String $description, $age, String $department, $phoneNumber, User $user,  String $chipOrTatoo,  String $identificationNumber)
    {
        parent::__construct($name, $species, $race, $vaccinated,  $sex, $description, $age, $department, $phoneNumber, $user);
        $this->chipOrTatoo = $chipOrTatoo;
        $this->identificationNumber = $identificationNumber;
    }


    public function getChipOrTatoo(): ?string
    {
        return $this->chipOrTatoo;
    }

    public function setChipOrTatoo(string $chipOrTatoo): self
    {
        $this->chipOrTatoo = $chipOrTatoo;

        return $this;
    }

    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(string $identificationNumber): self
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }
}
