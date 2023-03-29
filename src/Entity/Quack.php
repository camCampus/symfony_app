<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuackRepository::class)]
#[ApiResource]
class Quack
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contend = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt;

    #[ORM\OneToMany(mappedBy: 'quack_id', targetEntity: Comment::class)]
    private Collection $comments_id;

    #[ORM\ManyToOne(inversedBy: 'quacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Duck $duck = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->comments_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContend(): ?string
    {
        return $this->contend;
    }

    public function setContend(?string $contend): self
    {
        $this->contend = $contend;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentsId(): Collection
    {
        return $this->comments_id;
    }

    public function addCommentsId(Comment $commentsId): self
    {
        if (!$this->comments_id->contains($commentsId)) {
            $this->comments_id->add($commentsId);
            $commentsId->setQuackId($this);
        }

        return $this;
    }

    public function removeCommentsId(Comment $commentsId): self
    {
        if ($this->comments_id->removeElement($commentsId)) {
            // set the owning side to null (unless already changed)
            if ($commentsId->getQuackId() === $this) {
                $commentsId->setQuackId(null);
            }
        }

        return $this;
    }

    public function getDuck(): ?Duck
    {
        return $this->duck;
    }

    public function setDuck(?Duck $duck): self
    {
        $this->duck = $duck;

        return $this;
    }


}
