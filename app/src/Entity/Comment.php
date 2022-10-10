<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="Comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\MaxDepth(2)
     */
    private ?Animal $animal;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsSent")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\MaxDepth(1)
     */
    private ?User $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsReceived")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\MaxDepth(1)
     */
    private ?User $receiver;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $content;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="answers")
     * @Serializer\MaxDepth(1)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Comment $answerTo;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="answerTo")
     * @Serializer\MaxDepth(2)
     */
    private  $answers;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isRead;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimalId(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimalId(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAnswerTo(): ?self
    {
        return $this->answerTo;
    }

    public function setAnswerTo(?self $answerTo): self
    {
        $this->answerTo = $answerTo;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(self $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setAnswerTo($this);
        }

        return $this;
    }

    public function removeAnswer(self $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getAnswerTo() === $this) {
                $answer->setAnswerTo(null);
            }
        }

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

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
}
