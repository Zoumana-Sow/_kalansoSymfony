<?php

namespace App\Repository;

use App\Entity\GroupeTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupeTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeTag[]    findAll()
 * @method GroupeTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeTag::class);
    }

    // /**
    //  * @return GroupeTag[] Returns an array of GroupeTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupeTag
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
