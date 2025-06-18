<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[UniqueEntity(
    fields: ['email', 'user'],
    message: 'Un client avec cette adresse email existe déjà pour ce compte.',
    errorPath: 'email'
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'email est requis.")]
    #[Assert\Email(message: 'Veuillez fournir une adresse email valide.')]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'userUsers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Une erreur s'est produite lors de la mise à jour des données du client. Veuillez contacter un administrateur.")]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le prénom est requis.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Groups(['customer:read', 'customer:write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom est requis.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Groups(['customer:read', 'customer:write'])]
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
