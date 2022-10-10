<?php

namespace App\Entity;

use App\Repository\ReptileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReptileRepository::class)
 */
class Reptile extends Animal
{

}
