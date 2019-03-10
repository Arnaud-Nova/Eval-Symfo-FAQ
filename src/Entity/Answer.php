<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Question", mappedBy="validatedAnswer", cascade={"persist", "remove"})
     */
    private $validatedForQuestion;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getValidatedForQuestion(): ?Question
    {
        return $this->validatedForQuestion;
    }

    public function setValidatedForQuestion(?Question $validatedForQuestion): self
    {
        $this->validatedForQuestion = $validatedForQuestion;

        // set (or unset) the owning side of the relation if necessary
        $newValidatedAnswer = $validatedForQuestion === null ? null : $this;
        if ($newValidatedAnswer !== $validatedForQuestion->getValidatedAnswer()) {
            $validatedForQuestion->setValidatedAnswer($newValidatedAnswer);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist 
     * @ORM\PreUpdate
     */
    public function defaultValues()
    {
        if (!$this->createdAt) {
            $date = new \DateTime();
            $this->createdAt = $date;
        }

        if (!$this->isActive) {
            $this->isActive = true;
        }
    }
}
