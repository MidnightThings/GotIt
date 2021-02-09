<?php

namespace App\Repository;

use App\Entity\Kurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Kurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kurs[]    findAll()
 * @method Kurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kurs::class);
    }

    // /**
    //  * @return Kurs[] Returns an array of Kurs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kurs
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
