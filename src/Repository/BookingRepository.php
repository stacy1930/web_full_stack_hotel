<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }


    /**
     * check si room est libre aux dates données.
     * 
     * @param Booking
     *
     * @return bool
     */
    public function checkDispo(Booking $booking)
    {
        // initialisation de variable
        $st = $booking->getStartDate()->format('Y-m-d');
        $ed = $booking->getEndDate()->format('Y-m-d');

        $qb = $this->createQueryBuilder('b');
        // le b represente l objet booking
        $qb = $qb
            ->innerJoin('b.room', 'r')
            ->andWhere('r.id = :roomId') //prepare une close
            ->setParameter('roomId', $booking->getRoom()->getId())
            // Mise en place de la condition avec placeholder
            // ->andWhere("(b.startDate BETWEEN :std AND :edd or b.endDate BETWEEN :std AND :edd)")
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->between('b.startDate', ":std", ":edd"),
                    $qb->expr()->between('b.endDate', ":std", ":edd")
                )
            )
            // On parametre
            ->setParameter('std', $st)
            ->setParameter('edd', $ed);

        $result = $qb->getQuery()->getResult();
        // dd($result);

        return count($result) == 0;
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
