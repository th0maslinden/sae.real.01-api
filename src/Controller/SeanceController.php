<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Seance;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class SeanceController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/seances', name: 'api_seances', methods: ['GET'])]
    public function getSeances(SeanceRepository $seanceRepository): JsonResponse
    {
        $seances = $seanceRepository->findAll();

        $data = array_map(function ($seance) {
            return [
                'id' => $seance->getId(),
                'raison' => $seance->getRaison(),
                'date' => $seance->getDate(),
                'heureDebut' => $seance->getHeureDebut(),
                'heureFin' => $seance->getHeureFin(),
                'patient' => [
                    'firstname' => $seance->getPatient()->getFirstname(),
                    'lastname' => $seance->getPatient()->getLastname(),
                ],
                'professionnel' => [
                    'firstname' => $seance->getProfessionnel()->getFirstname(),
                    'lastname' => $seance->getProfessionnel()->getLastname(),
                ],
            ];
        }, $seances);

        return $this->json($data);
    }

    #[Route('/api/seances/create', name: 'create_seance', methods: ['POST'])]
    public function createSeance(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_PROFESSIONNEL')) {
            return new JsonResponse(['message' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        $seance = new Seance();
        $seance->setRaison($data['raison'] ?? 'Consultation');
        $seance->setNote($data['note'] ?? '');

        try {
            $seance->setDate(new \DateTime($data['date']));
            $seance->setHeureDebut(new \DateTime($data['start']));
            $seance->setHeureFin(new \DateTime($data['end']));
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
        }

        $patient = $entityManager->getRepository(User::class)->find($data['patient_id'] ?? null);
        $professionnel = $entityManager->getRepository(User::class)->find($data['professionnel_id'] ?? null);

        if (!$patient || !$professionnel) {
            return new JsonResponse(['message' => 'Patient ou professionnel invalide'], Response::HTTP_BAD_REQUEST);
        }

        $seance->setPatient($patient);
        $seance->setProfessionnel($professionnel);

        $entityManager->persist($seance);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Séance ajoutée avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/seances/{id}/update', name: 'update_seance', methods: ['PUT'])]
    public function updateSeance(Request $request, Seance $seance, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['date'])) {
            try {
                $seance->setDate(new \DateTime($data['date']));
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
            }
        }

        if (isset($data['start'])) {
            try {
                $seance->setHeureDebut(new \DateTime($data['start']));
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
            }
        }

        if (isset($data['end'])) {
            try {
                $seance->setHeureFin(new \DateTime($data['end']));
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
            }
        }

        if (isset($data['raison'])) {
            $seance->setRaison($data['raison']);
        }

        if (isset($data['note'])) {
            $seance->setNote($data['note']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Séance mise à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/seances/{id}/delete', name: 'delete_seance', methods: ['DELETE'])]
    public function deleteSeance(Seance $seance, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($seance);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Séance supprimée avec succès'], Response::HTTP_OK);
    }
}
