<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $companies = $manager->getRepository('App\Entity\Company')->findAll();

        // Crée des emplois pour chaque entreprise
        foreach ($companies as $company) {
            // Crée 2 emplois pour chaque entreprise
            for ($i = 0; $i < 2; $i++) {
                $job = new Job();
                $job
                    ->setTitle($faker->jobTitle)
                    ->setAdress($faker->address)
                    ->setDescription($faker->text)
                    ->setStartDate($faker->dateTimeBetween('-1 month', '+1 month'))
                    ->setSalary($faker->numerify("#####"))
                    ->setCity($faker->city) // Add city here
                    ->setCompany($company);

                $manager->persist($job);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Indique que cette fixture dépend de UserFixtures, CompanyFixtures, et CandidateFixtures
        // Cela garantit que les utilisateurs, entreprises et candidats sont créés avant les emplois
        return [
            UserFixtures::class,
            CompanyFixtures::class,
            CandidateFixtures::class
        ];
    }
}
