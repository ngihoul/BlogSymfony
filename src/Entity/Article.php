<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $voteCounter = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastModified;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="modifiedArticles")
     */
    private $LastModifiedBy;

    public function __construct()
    {
        $this->setCreationDate(new DateTime());
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getVoteCounter(): ?int
    {
        return $this->voteCounter;
    }

    public function setVoteCounter(?int $voteCounter): self
    {
        $this->voteCounter = $voteCounter <= 0 ? 0 : $voteCounter;

        return $this;
    }

    public function upVote()
    {
        $newCounter = $this->getVoteCounter();
        $this->setVoteCounter(++$newCounter);
    }

    public function downVote()
    {
        $newCounter = $this->getVoteCounter();
        $this->setVoteCounter(--$newCounter);
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(?\DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastModifiedBy(): ?User
    {
        return $this->LastModifiedBy;
    }

    public function setLastModifiedBy(?User $LastModifiedBy): self
    {
        $this->LastModifiedBy = $LastModifiedBy;

        return $this;
    }
}