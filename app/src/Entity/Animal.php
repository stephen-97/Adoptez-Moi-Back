<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "chien" = "Chien",
 *     "chat" = "Chat",
 *     "volatile" = "Volatile",
 *     "reptile" = "Reptile",
 *     "autres"= "Autres",
 *   })
 */
abstract class Animal
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $age;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $race;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $department;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animals", fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"user"})
     * @Serializer\MaxDepth(1)
     */
    private User $User;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({"list"})
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="animal", fetch="LAZY")
     * @Serializer\Groups({"list"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list"})
     */
    private string $sex;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list"})
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=20)
     * @Serializer\Groups({"list"})
     */
    private string $species;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorites")
     * @ORM\JoinTable(name="favorite_user_animal")
     * @Serializer\MaxDepth(depth=1)
     */
    private  $favorite;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="animal", orphanRemoval=true, fetch="LAZY")
     * @Serializer\MaxDepth(depth=1)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $vaccinated;

    public function __construct(String $name, String $species, String $race, $vaccinated,  String $sex, String $description, $age, String $department, $phoneNumber, User $user)
    {
        $this->name = $name;
        $this->species = $species;
        $this->race = $race;
        $this->vaccinated = $vaccinated;
        $this->sex = $sex;
        $this->description = $description;
        $this->age = $age;
        $this->department = $department;
        $this->phoneNumber = $phoneNumber;
        $this->User = $user;
        $this->createdAt = new \DateTimeImmutable('now');
        $this->images = new ArrayCollection();
        $this->favorite = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): self
    {
        $this->age = $age;

        return $this;
    }


    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnimal($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnimal() === $this) {
                $image->setAnimal(null);
            }
        }

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        $this->favorite->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAnimalId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAnimalId() === $this) {
                $comment->setAnimalId(null);
            }
        }

        return $this;
    }

    public function getVaccinated(): ?bool
    {
        return $this->vaccinated;
    }

    public function setVaccinated(bool $vaccinated): self
    {
        $this->vaccinated = $vaccinated;

        return $this;
    }

}
