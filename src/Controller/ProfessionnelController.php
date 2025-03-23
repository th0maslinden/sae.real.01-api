<?php

namespace App\Controller;

use App\Repository\ProfessionnelRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ProfessionnelController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(Request $request, ProfessionnelRepository $professionnelRepository): JsonResponse
    {
        $search = $request->query->get('search', '');

        $results = [];

        if (!empty($search)) {
            $results = $professionnelRepository->createQueryBuilder('p')
                ->where('p.firstname LIKE :search')
                ->orWhere('p.lastname LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->getQuery()
                ->getResult();
        }

        $jsonContent = $this->serializer->serialize($results, 'json', [
            'groups' => ['search_result']
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

}
