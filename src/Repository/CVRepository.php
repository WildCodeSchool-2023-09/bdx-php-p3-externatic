<?php

namespace App\Repository;

use App\Entity\CVs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CVs>
 *
 * @method CVs|null find($id, $lockMode = null, $lockVersion = null)
 * @method CVs|null findOneBy(array $criteria, array $orderBy = null)
 * @method CVs[]    findAll()
 * @method CVs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CVs::class);
    }
}
