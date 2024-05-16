<?php

namespace App\Entity;
use App\Entity\User;
use App\Entity\Etudiant;
use App\Entity\Enseignant;
use App\Repository\LikeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: 'likes')]
#[ApiResource()]

class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'likes')] 
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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
    /**
     * @Assert\Callback()
     */
    public function validateUserType(ExecutionContextInterface $context)
    {
        $user = $this->getUser();

        if (!$user instanceof Etudiant && !$user instanceof Enseignant) {
            $context->buildViolation('Only Etudiant and Enseignant can like a post.')
                ->atPath('user')
                ->addViolation();
        }
    }
}
