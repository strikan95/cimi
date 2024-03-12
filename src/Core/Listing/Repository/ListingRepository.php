<?php

namespace App\Core\Listing\Repository;

use App\Core\Listing\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Listing>
 *
 * @method Listing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Listing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Listing[]    findAll()
 * @method Listing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    public function fetchListingDetails(int $id)
    {
        $qb = $this->createQueryBuilder('l');

        $newerThanToday = $qb->expr()->gte('r.endDate', 'CURRENT_DATE()');
        $data = $qb
            ->select('l, r')
            ->where('l.id = :id')
            ->leftJoin('l.rentPeriods', 'r', Join::WITH, $newerThanToday)
            ->orderBy('l.id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
        //->setParameter('currentDate', $currentMonth);

        return $data;
    }
}
