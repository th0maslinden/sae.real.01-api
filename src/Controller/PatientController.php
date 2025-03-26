<?php

namespace App\Controller;

// src/Controller/PatientController.php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class PatientController extends AbstractController
{
    #[Route('/api/patients/{id}/seances', name: 'get_patient_seances', methods: ['GET'])]
    public function getSeancesForPatient(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        // Récupérer le patient
        $patient = $entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            return $this->json([
                'error' => 'Patient non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Obtenir les séances associées au patient
        $seances = $patient->getSeances();

        // Serializer les séances
        $data = $serializer->normalize($seances, null, ['groups' => ['seance:read']]);

        return $this->json($data);
    }
}

