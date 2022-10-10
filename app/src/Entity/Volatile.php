<?php

namespace App\Entity;

use App\Repository\VolatileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VolatileRepository::class)
 */
class Volatile extends Animal
{

}
