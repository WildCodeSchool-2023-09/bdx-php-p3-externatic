<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Utilise getReference pour récupérer les utilisateurs avec le rôle ROLE_COMPANY
        $userRefs = [];
        for ($i = 0; $i < 2; $i++) {
            $userRef = 'company_user_' . $i;
            $userRefs[] = $this->getReference($userRef);
        }

        // Crée des entreprises liées à des utilisateurs ayant le rôle ROLE_COMPANY
        foreach ($userRefs as $companyUser) {
            $company = new Company();
            $company
                ->setSiret($faker->numerify("##############"))
                ->setAdress($faker->address)
                ->setUser($companyUser);

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Indique que cette fixture dépend de UserFixtures
        return [
            UserFixtures::class,
        ];
    }
}
