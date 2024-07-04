<?php

namespace App\Core\Search;

use App\Core\Listing\Entity\Listing;
use App\Shared\ORM\ListingStatus\ListingStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class ListingQueryBuilder
{
    public static int $PAGE_SIZE = 10;
    private QueryBuilder $qb;

    public function __construct(
        private readonly Request $request,
        private readonly EntityManagerInterface $em,
    ) {
        $this->qb = $this->em
            ->createQueryBuilder()
            ->select('l as listing')
            ->from(Listing::class, 'l')
            ->andWhere('l.status = :published')
            ->groupBy('l.id')
            ->setParameter('published', ListingStatusType::STATUS_APPROVED);
    }

    public function executeQuery()
    {
        return $this->qb->getQuery()->execute();
    }

    public function getQb(): QueryBuilder
    {
        return $this->qb;
    }

    /*    private function page($value): void
    {
        $this->qb->setFirstResult($value * self::PAGE_SIZE);
    }*/

    private function from($value): void
    {
        $fromDate = new \DateTime($value);
        $toDate = new \DateTime($value);
        $toDate->modify('+1 day');

        $this->qb
            ->leftJoin('l.rentPeriods', 'ar')
            ->addSelect(
                'SUM(CASE WHEN :fromDate BETWEEN ar.startDate AND ar.endDate OR :toDate BETWEEN ar.startDate AND ar.endDate THEN 1 ELSE 0 END) AS overlapCount',
            )
            ->andHaving('overlapCount = 0')
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate);
    }

    private function to($value): void
    {
        $toDate = new \DateTime($value);
        $this->qb->setParameter('toDate', $toDate);
    }

    private function hostId($id): void
    {
        $this->qb->andWhere('l.host = :id')->setParameter('id', $id);
    }

    private function amenities($ids): void
    {
        $this->qb
            ->leftJoin('l.amenities', 'la')
            ->andWhere('la.id IN (:amenityIds)')
            ->andHaving('COUNT(DISTINCT la.id) = :amenityCount')
            ->setParameter('amenityIds', $ids)
            ->setParameter(':amenityCount', count($ids));
    }

    private function priceMin($value): void
    {
        $this->qb
            ->andWhere($this->qb->expr()->gt('l.price', ':priceMin'))
            ->setParameter('priceMin', $value);
    }

    private function priceMax($value): void
    {
        $this->qb
            ->andWhere($this->qb->expr()->lt('l.price', ':priceMax'))
            ->setParameter('priceMax', $value);
    }

    //lat,long
    private function poi($point): void
    {
        [$lat, $long] = sscanf($point, '%f,%f');

        $this->qb
            ->addSelect(
                "ST_Distance(l.location.coordinates, ST_GeomFromText(:point, 4326), 'kilometre') AS distance",
            )
            ->andHaving('distance <= :radius')
            ->setParameter('point', sprintf('Point(%f %f)', $lat, $long))
            ->addOrderBy('distance');

        if (!array_key_exists('radius', $this->all())) {
            $this->qb->setParameter('radius', 20);
        }
    }

    private function radius($value): void
    {
        if (array_key_exists('poi', $this->all())) {
            $this->qb->setParameter(':radius', $value);
        }
    }

    private function all(): array
    {
        return $this->request->query->all();
    }

    public function apply(): void
    {
        foreach ($this->all() as $name => $value) {
            $method = str_replace('_', '', lcfirst(ucwords($name, '_')));

            if (
                method_exists($this, $method) &&
                is_callable([$this, $method])
            ) {
                $this->$method($value);
            }
        }
    }
}
