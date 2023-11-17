<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    #[Route('/sorties/creation', name:'sortie_creation')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();


        $sortieForm = $this-> createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'Votre sortie a bien été créée');
                return $this->redirectToRoute('sortie_infos', ['id' => $sortie->getId()]);
            }

        return $this->render('Main/sortie.create.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    #[Route('/sorties', name:'sortie_liste')]
    public function lister(): Response
    {
        return $this->render('');
    }

    #[Route('/sorties/infos/{id}', name: 'sortie_infos')]
    public function infos_sortie(SortieRepository $sortieRepository, int $id): Response
    {
        $sortie = $sortieRepository->find($id);


        return $this->render('Main/sortie.afficher.html.twig', [
            "sortie" => $sortie
            ]
         );
    }

}