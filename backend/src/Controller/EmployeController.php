<?php

namespace App\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employe')]
class EmployeController extends AbstractController
{
    #[Route('/feedback/authorization', methods: ['PATCH'])]
    public function authorizeFeedback(Request $request, EntityManagerInterface $em): JsonResponse
    {
    $user = $this->getUser();

    if (!$user || !in_array('ROLE_EMPLOYE', $user->getRoles())) {
        return $this->json(['error' => 'Accès refusé'], 403);
    }

    $data = json_decode($request->getContent(), true);
    $avisId = $data['avis_id'] ?? null;
    $action = $data['action'] ?? null;

    if (!$avisId || !in_array($action, ['approve', 'reject'])) {
        return $this->json(['error' => 'Requête invalide'], 400);
    }

    $avis = $em->getRepository(Avis::class)->find($avisId);
    if (!$avis) {
        return $this->json(['error' => 'Avis introuvable'], 404);
    }

    $avis->setStatus($action === 'approve' ? 'validé' : 'refusé');
    $em->persist($avis); 
    $em->flush();

    return $this->json(['success' => 'Avis mis à jour', 'new_status' => $avis->getStatus()]);
}


    #[Route('/avis/a-traiter', methods: ['GET'])]
    public function getPendingAvis(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user || !in_array('ROLE_EMPLOYE', $user->getRoles())) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }
    
        $avisList = $em->getRepository(Avis::class)->createQueryBuilder('a')
        ->where('a.status IN (:statuses)')
        ->setParameter('statuses', ['à traiter', 'en attente'])
        ->orderBy('a.ride', 'DESC')
        ->getQuery()
        ->getResult();  

        $data = [];
    
        foreach ($avisList as $avis) {
            $data[] = [
                'id' => $avis->getId(),
                'pseudo' => $avis->getPassenger()?->getPseudo(),
                'driverPseudo' => $avis->getRide()?->getDriver()?->getPseudo(),
                'rideId' => $avis->getRide()?->getId(),
                'departureCity' => $avis->getRide()?->getDeparture(),
                'dateDepart' => $avis->getRide()?->getDate()?->format('Y-m-d'),
                'rating' => $avis->getRating(),
                'status' => $avis->getStatus(),
                'comment' => $avis->getComment() ?? 'Aucun commentaire.'
            ];
        }
    
        return $this->json($data);
    }



    #[Route('/avis/historique', methods: ['GET'])]
    public function getArchivedAvis(EntityManagerInterface $em): JsonResponse
    {
    $user = $this->getUser();

    if (!$user || !in_array('ROLE_EMPLOYE', $user->getRoles())) {
        return $this->json(['error' => 'Accès refusé'], 403);
    }

    $avisList = $em->getRepository(Avis::class)->createQueryBuilder('a')
        ->where('a.status IN (:statuses)')
        ->setParameter('statuses', ['validé', 'refusé'])
        ->orderBy('a.ride', 'DESC')
        ->getQuery()
        ->getResult();

    $data = [];

    foreach ($avisList as $avis) {
        $data[] = [
            'id' => $avis->getId(),
            'pseudo' => $avis->getPassenger()?->getPseudo(),
            'driverPseudo' => $avis->getRide()?->getDriver()?->getPseudo(),
            'rideId' => $avis->getRide()?->getId(),
            'departureCity' => $avis->getRide()?->getDeparture(),
            'dateDepart' => $avis->getRide()?->getDate()?->format('Y-m-d'),
            'rating' => $avis->getRating(),
            'status' => $avis->getStatus(),
            'comment' => $avis->getComment() ?? 'Aucun commentaire.'
        ];
    }

    return $this->json($data);
}


}
