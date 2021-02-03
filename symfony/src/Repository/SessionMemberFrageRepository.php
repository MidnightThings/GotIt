<?php

namespace App\Repository;

use App\Entity\SessionMemberFrage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SessionMemberFrage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionMemberFrage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionMemberFrage[]    findAll()
 * @method SessionMemberFrage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionMemberFrageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionMemberFrage::class);
    }

    // /**
    //  * @return SessionMemberFrage[] Returns an array of SessionMemberFrage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SessionMemberFrage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
