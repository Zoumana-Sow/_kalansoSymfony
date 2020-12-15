<?php

namespace App\Repository;

use App\Entity\Promos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promos[]    findAll()
 * @method Promos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promos::class);
    }

    // /**
    //  * @return Promos[] Returns an array of Promos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Promos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
