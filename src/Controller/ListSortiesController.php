<?php

namespace App\Controller;

use App\DTO\SortiesFilterDTO;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortiesFilterType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// TODO refactor with Sortie controller maybe
#[Route('/list', name: 'list_')]
class ListSortiesController extends AbstractController
{
    #[Route('', name: 'main')]
    public function main(SortieRepository $sortieRepository, Request $request): Response
    {
        /** @var Participant $user */
        $user = $this->getUser();

        $filters = new SortiesFilterDTO();
        $filters->campus = $user->getCampus();
        $sortiesFilterForm = $this->createForm(SortiesFilterType::class, $filters);
        $sortiesFilterForm->handleRequest($request);
    
        $sorties = $sortieRepository->findSortiesWithFilters($filters, $user->getId());

        return $this->render('temp/list.html.twig', [
            'user' => $user,
            'sorties' => $sorties,
            'sortiesFilterForm' => $sortiesFilterForm
        ]);
    }

    #[Route('/sorties/{id}/inscription', name: 'sortie_inscription')]
    public function inscription(EntityManagerInterface $entityManager, int $id) : Response
    {
        /** @var Participant $user */
        $user = $this->getUser();
        $date = new \DateTime();

        $sortie = $entityManager->getRepository(Sortie::class)->find($id);

        
        $dateFinInscription = $sortie->getDateLimiteInscription();
        
        if (count($sortie->getParticipants()) >= $sortie->getNbInscriptionsMax()) {
            $this->addFlash(type: 'success', message: 'Desole, il n\'y a plus de place.');
        } else {
            if ($date>$dateFinInscription) {
                $this->addFlash(type:'success', message:'Vous ne pouvez plus vous inscrire, la date limite d\'inscription est dépassée.');
                return $this->redirectToRoute('list_main');
            }

            $sortie->addParticipant($user);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash(type:'success', message:'Votre inscription a été enregistrée avec succès');
        }
        return $this->redirectToRoute('list_main');

    }

    #[Route('/sorties/{id}/desinscription', name: 'sortie_desinscription')]
    public function desinscription(EntityManagerInterface $entityManager, int $id) : Response
    {
        /** @var Participant $user */
        $user = $this->getUser();

        $sortie = $entityManager->getRepository(Sortie::class)->find($id);

        $sortie->removeParticipant($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash(type:'success', message:'Vous vous êtes désinscrit de la sortie avec succès');
        return $this->redirectToRoute('list_main');

    }

}
