<?php

namespace App\Core\Search\Controller;

use App\Core\Listing\Entity\Listing;
use App\Core\Search\ListingQueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class SearchController extends AbstractController
{
    private readonly array $serializationContext;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->serializationContext = (new ObjectNormalizerContextBuilder())
            ->withGroups(['list'])
            ->toArray();
    }

    #[Route('/api/v1/search', name: 'api.v1.search.index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $qb = new ListingQueryBuilder($request, $this->em);
        $qb->apply();
        //$listings = $qb->executeQuery();

        //$q = $this->em->getRepository(Listing::class)
        //    ->createQueryBuilder('l')
        //    ->setFirstResult(0)
        //    ->setMaxResults(20)
        //    ->getQuery();

        $paginator = new Paginator($qb->getQb(), fetchJoinCollection: true);
        $c = count($paginator);
        $pagesCount = ceil($c / ListingQueryBuilder::$PAGE_SIZE);

        $page = 1;
        if (array_key_exists('page', $request->query->all())) {
            $page = $request->query->get('page');
        }

        $qb->getQb()
            ->setFirstResult(ListingQueryBuilder::$PAGE_SIZE * ($page - 1)) // set the offset
            ->setMaxResults(ListingQueryBuilder::$PAGE_SIZE); // set the limit

        $listings = $qb->executeQuery();

        $res = ['listings' => [...$listings], 'pages' => $pagesCount];

        return $this->json(
            $res,
            Response::HTTP_OK,
            context: $this->serializationContext,
        );
    }
}
