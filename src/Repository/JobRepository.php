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

        if ($title !== null) {
            $queryBuilder
                ->andWhere('j.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

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
            ->innerJoin('j.likingUsers', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
