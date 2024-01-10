<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CandidateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Utilise getReference pour récupérer les utilisateurs avec le rôle ROLE_CANDIDATE
        $userRefs = [];
        for ($i = 0; $i < 2; $i++) {
            $userRef = 'candidate_user_' . $i;
            $userRefs[] = $this->getReference($userRef);
        }

        foreach ($userRefs as $candidateUser) {
            $candidate = new Candidate();
            $candidate
                ->setGithub($faker->url)
                ->setUser($candidateUser)
                ->setFonction($faker->jobTitle);
                $this->setReference('candidat', $candidate);

            $manager->persist($candidate);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
