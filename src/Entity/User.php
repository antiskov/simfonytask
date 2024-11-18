<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[UniqueEntity('id', 'The id should be unique')]
#[UniqueEntity('login', 'The login should be unique')]
#[UniqueEntity('pass', 'The pass should be unique')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 8)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    
    #[Assert\Type(type: 'string')]
    private ?string $id = null;

    #[ORM\Column(length: 8, unique: true)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    #[Assert\Type(type: 'string')]
    private ?string $login = null;

    #[ORM\Column(length: 8, unique: true)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    #[Assert\Type(type: 'string')]
    private ?string $pass = null;

    #[ORM\Column(length: 8)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    #[Assert\Type(type: 'string')]
    private ?string $phone = null;

    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): static
    {
        $this->pass = $pass;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function getPassword(): string
    {
        return $this->pass;
    }
}
