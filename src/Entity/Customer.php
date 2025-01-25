<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    /**
     * @var Collection<int, CustomerUser>
     */
    #[ORM\OneToMany(targetEntity: CustomerUser::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $users;

    /**
     * @var Collection<int, CustomerUser>
     */
    #[ORM\OneToMany(targetEntity: CustomerUser::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $customerUsers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->customerUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    /**
     * @return Collection<int, CustomerUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(CustomerUser $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(CustomerUser $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomerUser>
     */
    public function getCustomerUsers(): Collection
    {
        return $this->customerUsers;
    }

    public function addCustomerUser(CustomerUser $customerUser): static
    {
        if (!$this->customerUsers->contains($customerUser)) {
            $this->customerUsers->add($customerUser);
            $customerUser->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerUser(CustomerUser $customerUser): static
    {
        if ($this->customerUsers->removeElement($customerUser)) {
            // set the owning side to null (unless already changed)
            if ($customerUser->getCustomer() === $this) {
                $customerUser->setCustomer(null);
            }
        }

        return $this;
    }
}
