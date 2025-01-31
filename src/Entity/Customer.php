<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Please provide a valid email address.')]
    #[Groups(['customer:read'])]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'userUsers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "An error occurred when trying to update this Customer data. Please contact an administrator.")]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Firstname is required.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Firstname cannot exceed {{ limit }} characters.'
    )]
    #[Groups(['customer:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Lastname is required.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Lastname cannot exceed {{ limit }} characters.'
    )]
    #[Groups(['customer:read'])]
    private ?string $lastname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
