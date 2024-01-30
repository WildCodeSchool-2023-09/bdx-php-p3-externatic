<?php

namespace App\Repository;

use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Candidate>
 *
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    public function searchByFunctionAndLocation(?string $fonction, ?string $location): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')// Jointure avec l'entité User (u)
            ->where('c.fonction LIKE :fonction')// Condition : la fonction doit correspondre au critère fourni
            ->andWhere('u.location LIKE :location')// Condition : la localisation doit correspondre au critère fourni
            ->setParameter('fonction', '%' . $fonction . '%')
            ->setParameter('location', '%' . $location . '%');

        // Paramètre lié à la valeur de la fonction/location fournie dans la barre de recherche.
        // La condition LIKE '%<fonction/location>%' permet de rechercher toutes les occurrences
        // où la valeur de la colonne 'fonction/location' contient la sous-chaîne spécifiée (<fonction/location>).

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByLikedCompany(UserInterface $company): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.likingCompanies', 'company')
            ->where('company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
    }
}
