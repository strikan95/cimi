<?php

namespace App\Core\Search;

use App\Core\Listing\Entity\Listing;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class ListingQueryBuilder
{
    private QueryBuilder $qb;

    public function __construct(
        private readonly Request $request,
        private readonly EntityManagerInterface $em,
    ) {
        $this->qb = $this->em
            ->createQueryBuilder()
            ->select('l as listing')
            ->from(Listing::class, 'l');
    }

    public function executeQuery()
    {
        return $this->qb->getQuery()->execute();
    }

    public function getQb(): QueryBuilder
    {
        return $this->qb;
    }

    private function hostId($id): void
    {
        $this->qb->andWhere('l.host = :id')->setParameter('id', $id);
    }

    private function amenityId($ids): void
    {
        $this->qb
            ->join('l.amenities', 'la', Expr\Join::ON)
            ->andWhere($this->qb->expr()->in('la.id', ':amenities'))
            ->addGroupBy('l.id')
            ->andHaving(
                $this->qb->expr()->eq('COUNT(DISTINCT la.id)', count($ids)),
            )
            ->setParameter('amenities', $ids);
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
