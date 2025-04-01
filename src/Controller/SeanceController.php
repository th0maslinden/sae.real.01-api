<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SeanceController extends AbstractController
{
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
}
