<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findPaginatedCustomers(User $user, int $page = 1, int $limit = 5): array
    {
        $qb = $this->createQueryBuilder('cu')
            ->where('cu.user = :user')
            ->setFirstResult(($page -1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->setParameter('user',$user)->getResult();
    }
}
