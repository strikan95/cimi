<?php

namespace App\Core\Host\Repository;

use App\Core\Listing\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HostListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    public function fetchHostListings(int $id)
    {
        $qb = $this->createQueryBuilder('l');
        return $qb
            ->select('l.id, l.title, l.description, l.coverImageUrl, l.status')
            ->where('l.host = :hostId')
            ->setParameter('hostId', $id)
            ->getQuery()
            ->getResult();
    }
}
