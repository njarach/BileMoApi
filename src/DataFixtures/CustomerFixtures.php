<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\CustomerUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{

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
                    ['firstname' => 'Alice', 'lastname' => 'Johnson'],
                    ['firstname' => 'Bob', 'lastname' => 'Smith'],
                    ['firstname' => 'Charlie', 'lastname' => 'Brown'],
                ],
            ],
            [
                'name' => 'Creative Agency',
                'company_name' => 'Creative Agency Ltd.',
                'users' => [
                    ['firstname' => 'Diana', 'lastname' => 'Taylor'],
                    ['firstname' => 'Edward', 'lastname' => 'Anderson'],
                ],
            ],
            [
                'name' => 'CloudWorks',
                'company_name' => 'CloudWorks LLC',
                'users' => [
                    ['firstname' => 'Fiona', 'lastname' => 'White'],
                    ['firstname' => 'George', 'lastname' => 'Black'],
                    ['firstname' => 'Hannah', 'lastname' => 'Green'],
                ],
            ],
            [
                'name' => 'Market Pro',
                'company_name' => 'Market Pro Corp.',
                'users' => [
                    ['firstname' => 'Ian', 'lastname' => 'Adams'],
                    ['firstname' => 'Julia', 'lastname' => 'Roberts'],
                ],
            ],
            [
                'name' => 'NextGen Innovations',
                'company_name' => 'NextGen Innovations Pvt. Ltd.',
                'users' => [
                    ['firstname' => 'Kyle', 'lastname' => 'Morris'],
                    ['firstname' => 'Laura', 'lastname' => 'Davis'],
                    ['firstname' => 'Michael', 'lastname' => 'Evans'],
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
                $user->setFirstname($userData['firstname']);
                $user->setLastname($userData['lastname']);
                $user->setCustomer($customer);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}