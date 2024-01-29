<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * @method Job[]    searchByTitleAndLocation(string $title, string $location): array
     */
    public function searchByTitleAndLocation(string $title, string $location): array
    {
        $queryBuilder = $this->createQueryBuilder('j');

        // Ajoute la condition de recherche par titre si un titre est fourni.
        if ($title !== null) {
            $queryBuilder
                ->andWhere('j.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

        // Ajoute la condition de recherche par emplacement si un emplacement est fourni.
        if ($location !== null) {
            $queryBuilder
                ->andWhere('j.city LIKE :location')
                ->setParameter('location', '%' . $location . '%');
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function findByLikedUser(UserInterface $user): array
    {
        return $this->createQueryBuilder('j')
            ->innerJoin('j.likingUsers', 'u')// Jointure avec la table des utilisateurs aimés
            ->where('u = :user')// Condition : l'utilisateur de la jointure est égal à l'utilisateur fourni en paramètre
            ->setParameter('user', $user)// Attribution de la valeur de l'utilisateur fourni en paramètre
            ->getQuery()
            ->getResult();
    }
}
