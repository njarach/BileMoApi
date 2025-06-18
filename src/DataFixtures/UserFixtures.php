<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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
        $usersData = [
            [
                'name' => 'Tech Solutions',
                'company_name' => 'Tech Solutions Inc.',
                'email'=>'techsolutions@techmail.com',
                'password'=>'password123',
                'customers' => [
                    ['firstname' => 'Alice', 'lastname' => 'Johnson', 'email' => 'alice.johnson@bing.com', 'password' => 'password123'],
                    ['firstname' => 'Bob', 'lastname' => 'Smith', 'email' => 'bob.smith@bing.com', 'password' => 'password123'],
                    ['firstname' => 'Charlie', 'lastname' => 'Brown', 'email' => 'charlie.brown@bing.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'Creative Agency',
                'company_name' => 'Creative Agency Ltd.',
                'email'=>'creative@creativemail.com',
                'password'=>'password123',
                'customers' => [
                    ['firstname' => 'Diana', 'lastname' => 'Taylor', 'email' => 'diana.taylor@outlook.com', 'password' => 'password123'],
                    ['firstname' => 'Edward', 'lastname' => 'Anderson', 'email' => 'edward.anderson@outlook.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'CloudWorks',
                'company_name' => 'CloudWorks LLC',
                'email'=>'cloud@cloudworksmail.com',
                'password'=>'password123',
                'customers' => [
                    ['firstname' => 'Fiona', 'lastname' => 'White', 'email' => 'fiona.white@gmail.com', 'password' => 'password123'],
                    ['firstname' => 'George', 'lastname' => 'Black', 'email' => 'george.black@gmail.com', 'password' => 'password123'],
                    ['firstname' => 'Hannah', 'lastname' => 'Green', 'email' => 'hannah.green@gmail.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'Market Pro',
                'company_name' => 'Market Pro Corp.',
                'email'=>'market@marketpromail.com',
                'password'=>'password123',
                'customers' => [
                    ['firstname' => 'Ian', 'lastname' => 'Adams', 'email' => 'ian.adams@wanadoo.com', 'password' => 'password123'],
                    ['firstname' => 'Julia', 'lastname' => 'Roberts', 'email' => 'julia.roberts@wanadoo.com', 'password' => 'password123'],
                ],
            ],
            [
                'name' => 'NextGen Innovations',
                'company_name' => 'NextGen Innovations Pvt. Ltd.',
                'email'=>'nextgen@nextmail.com',
                'password'=>'password123',
                'customers' => [
                    ['firstname' => 'Kyle', 'lastname' => 'Morris', 'email' => 'kyle.morris@hotmail.com'],
                    ['firstname' => 'Laura', 'lastname' => 'Davis', 'email' => 'laura.davis@hotmail.com'],
                    ['firstname' => 'Michael', 'lastname' => 'Evans', 'email' => 'michael.evans@hotmail.com'],
                ],
            ],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setName($userData['name']);
            $user->setRoles(['ROLE_USER']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->setCompanyName($userData['company_name']);
            $manager->persist($user);

            foreach ($userData['customers'] as $customerData) {
                $customer = new Customer();
                $customer->setEmail($customerData['email']);
                $customer->setFirstname($customerData['firstname']);
                $customer->setLastname($customerData['lastname']);
                $customer->setUser($user);
                $manager->persist($customer);
            }
        }
        $manager->flush();
    }
}