<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $query = $this->createQueryBuilder('j')
            ->andWhere('j.title LIKE :title')
            ->andWhere('j.city LIKE :location')
            ->setParameter('title', '%' . $title . '%')
            ->setParameter('location', '%' . $location . '%')
            ->getQuery();

        return $query->getResult();
    }
}
