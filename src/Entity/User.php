<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Security\Service\Hasher\Password\User\UserPasswordHasherInterface;
use App\Contracts\Security\Entity\AuthUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use SensitiveParameter;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements AuthUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $ulid;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:details'])]
    private string $email;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'owner', orphanRemoval: true)]
    #[Groups(['user:details'])]
    private Collection&Selectable $posts;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'owner', orphanRemoval: true)]
    #[Groups(['user:details'])]
    private Collection&Selectable $images;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->ulid = (new Ulid())->toBase32();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(#[SensitiveParameter] ?string $password, UserPasswordHasherInterface $hasher): static
    {
        $this->password = is_null($password) ? null : $hasher->hash($this, $password);

        return $this;
    }

    #[Groups(['user:details'])]
    #[SerializedName('ulid')]
    public function getUlid(): string
    {
        return $this->ulid;
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }

    public function eraseCredentials(): void
    {
        // do nothing
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Collection&Selectable<int, Post>
     */
    public function getPosts(): Collection&Selectable
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setOwner($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getOwner() === $this) {
                $post->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setOwner($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getOwner() === $this) {
                $image->setOwner(null);
            }
        }

        return $this;
    }
}