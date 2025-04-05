<?php

namespace App\Controller;

use App\Entity\Professionnel;
use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        if (!empty($search)) {
            $results = $professionnelRepository->createQueryBuilder('p')
                ->where('p.firstname LIKE :search')
                ->orWhere('p.lastname LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->getQuery()
                ->getResult();
        } else {
            $results = $professionnelRepository->findAll();
        }

        $jsonContent = $this->serializer->serialize($results, 'json', [
            'groups' => ['search_result']
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/api/professionnels/{id}/seances', name: 'get_professionnel_seances', methods: ['GET'])]
    public function getSeancesForProfessionnel(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $professionnel = $entityManager->getRepository(Professionnel::class)->find($id);

        if (!$professionnel) {
            return $this->json([
                'error' => 'Professionnel non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $seances = $professionnel->getSeances();

        $data = $serializer->normalize($seances, null, ['groups' => ['seance:read']]);

        return $this->json($data);
    }
}
