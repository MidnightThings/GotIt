<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\SessionMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function getAnswers($userToken, $sessionId){
        $qb = $this->createQueryBuilder("s");
        $qb->select("smf.content, smf.id");
        $qb->leftJoin("s.sessionMembers", "sm");
        $qb->leftJoin("sm.sessionMemberFrages", "smf");
        $qb->where("s.id = :sessionId");
        $qb->andWhere("sm.id != :userToken");
        $qb->andWhere("s.frage = smf.frage");
        $qb->setParameter("userToken", $userToken);
        $qb->setParameter("sessionId", $sessionId);
        return $qb->getQuery()->getResult();
    }

    public function getCountFrageAnswered($sessionId){
        $qb = $this->createQueryBuilder("s");
        $qb->select("COUNT(smf) AS count");
        $qb->leftJoin("s.sessionMembers", "sm");
        $qb->leftJoin("sm.sessionMemberFrages", "smf");
        $qb->where("s.id = :sessionId");
        $qb->andWhere("s.frage = smf.frage");
        $qb->setParameter("sessionId", $sessionId);
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Session[] Returns an array of Session objects
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
    public function findOneBySomeField($value): ?Session
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
