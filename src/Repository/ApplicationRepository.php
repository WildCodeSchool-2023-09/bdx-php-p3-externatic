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
        // Construction de la requête SQL
        return $this->createQueryBuilder('a')
            ->leftJoin('a.curriculum', 'cvs')// Jointure avec l'entité Curriculum (cvs)
            ->andWhere('cvs.candidate = :candidate')// Condition : le candidat du curriculum doit être celui fourni
            ->setParameter('candidate', $candidate)// Paramètre lié à la valeur du candidat fourni
            ->getQuery()// Exécution de la requête
            ->getResult();// Récupération des résultats
    }
}
