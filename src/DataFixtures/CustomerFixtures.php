<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\CustomerUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $customersData = [
            [
                'name' => 'Tech Solutions',
                'company_name' => 'Tech Solutions Inc.',
                'users' => [
                    ['firstname' => 'Alice', 'lastname' => 'Johnson', 'email' => 'alice.johnson@techsolutions.com', 'password' => 'password123'],
                    ['firstname' => 'Bob', 'lastname' => 'Smith', 'email' => 'bob.smith@techsolutions.com', 'password' => 'password123'],
                    ['firstname' => 'Charlie', 'lastname' => 'Brown', 'email' => 'charlie.brown@techsolutions.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'Creative Agency',
                'company_name' => 'Creative Agency Ltd.',
                'users' => [
                    ['firstname' => 'Diana', 'lastname' => 'Taylor', 'email' => 'diana.taylor@creativeagency.com', 'password' => 'password123'],
                    ['firstname' => 'Edward', 'lastname' => 'Anderson', 'email' => 'edward.anderson@creativeagency.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'CloudWorks',
                'company_name' => 'CloudWorks LLC',
                'users' => [
                    ['firstname' => 'Fiona', 'lastname' => 'White', 'email' => 'fiona.white@cloudworks.com', 'password' => 'password123'],
                    ['firstname' => 'George', 'lastname' => 'Black', 'email' => 'george.black@cloudworks.com', 'password' => 'password123'],
                    ['firstname' => 'Hannah', 'lastname' => 'Green', 'email' => 'hannah.green@cloudworks.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'Market Pro',
                'company_name' => 'Market Pro Corp.',
                'users' => [
                    ['firstname' => 'Ian', 'lastname' => 'Adams', 'email' => 'ian.adams@marketpro.com', 'password' => 'password123'],
                    ['firstname' => 'Julia', 'lastname' => 'Roberts', 'email' => 'julia.roberts@marketpro.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'NextGen Innovations',
                'company_name' => 'NextGen Innovations Pvt. Ltd.',
                'users' => [
                    ['firstname' => 'Kyle', 'lastname' => 'Morris', 'email' => 'kyle.morris@nextgeninnovations.com', 'password' => 'password123'],
                    ['firstname' => 'Laura', 'lastname' => 'Davis', 'email' => 'laura.davis@nextgeninnovations.com', 'password' => 'password123'],
                    ['firstname' => 'Michael', 'lastname' => 'Evans', 'email' => 'michael.evans@nextgeninnovations.com', 'password' => 'password123'],
                ],
            ],
        ];

        foreach ($customersData as $customerData) {
            $customer = new Customer();
            $customer->setName($customerData['name']);
            $customer->setCompanyName($customerData['company_name']);
            $manager->persist($customer);

            foreach ($customerData['users'] as $userData) {
                $user = new CustomerUser();
                $user->setEmail($userData['email']);
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
                $user->setFirstname($userData['firstname']);
                $user->setLastname($userData['lastname']);
                $user->setCustomer($customer);

                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}