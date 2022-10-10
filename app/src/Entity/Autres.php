<?php

namespace App\Entity;

use App\Repository\AutresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutresRepository::class)
 */
class Autres extends Animal
{
}
