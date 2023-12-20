<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Tableau initial d'administrateur
        $adminInfos = [
            [
                'email' => 'admin@mail.fr',
                'password' => 'motdepasse',
                'username' => 'admin',
                'image' => 'admin_image.jpg',
                'bio' => 'admin_bio',
                'linkedin' => 'admin_linkedin',
                'location' => 'bordeaux',
                'role' => 'ROLE_ADMIN',
            ],
        ];

        // Création de l'administrateur à partir du tableau initial
        $adminInfo = $adminInfos[0];
        $admin = new User();
        $admin
            ->setEmail($adminInfo['email'])
            ->setPassword($this->passwordHasher->hashPassword($admin, $adminInfo['password']))
            ->setUsername($adminInfo['username'])
            //->setImage($adminInfo['image'])
            ->setBio($adminInfo['bio'])
            ->setLinkedin($adminInfo['linkedin'])
            ->setLocation($adminInfo['location'])
            ->setRoles([$adminInfo['role']]);

        $manager->persist($admin);

        // Création de 2 candidats avec Faker
        for ($i = 0; $i < 2; $i++) {
            $candidat = new User();
            $candidat
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword($candidat, $faker->password))
                ->setUsername($faker->userName)
                //->setImage($faker->imageUrl())
                ->setBio($faker->sentence)
                ->setLinkedin($faker->url)
                ->setLocation($faker->city)
                ->setRoles(['ROLE_CANDIDAT']);

            $manager->persist($candidat);
        }

        // Création de 2 entreprises avec Faker
        for ($i = 0; $i < 2; $i++) {
            $company = new User();
            $company
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword($company, $faker->password))
                ->setUsername($faker->userName)
                //->setImage($faker->imageUrl())
                ->setBio($faker->sentence)
                ->setLinkedin($faker->url)
                ->setLocation($faker->city)
                ->setRoles(['ROLE_COMPANY']);

            $manager->persist($company);
        }

        $manager->flush();
    }
}
