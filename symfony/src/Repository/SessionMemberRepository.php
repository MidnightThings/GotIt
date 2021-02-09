<?php

namespace App\Repository;

use App\Entity\SessionMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SessionMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionMember[]    findAll()
 * @method SessionMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionMember::class);
    }

    // /**
    //  * @return SessionMember[] Returns an array of SessionMember objects
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
    public function findOneBySomeField($value): ?SessionMember
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
