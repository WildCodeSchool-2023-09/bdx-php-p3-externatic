<?php

namespace App\Repository;

use App\Entity\Application;
use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Application>
 *
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    /**
     * @param Candidate $candidate
     * @return Application[]
     */
    public function findByCandidate(Candidate $candidate): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.curriculum', 'cvs')
            ->andWhere('cvs.candidate = :candidate')
            ->setParameter('candidate', $candidate)
            ->getQuery()
            ->getResult();
    }
}
