<?php

namespace App\Controller;

use App\Entity\Etat;
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
use function Symfony\Component\String\s;

class SortieController extends AbstractController
{

    #[Route('/sorties/creation', name:'sortie_creation')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();


        $sortieForm = $this-> createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

                /** @var Participant $user */
                $user = $this->getUser();
                $sortie->setOrganisateur($user);

                $campus = $user->getCampus();
                $sortie->setCampus($campus);

                if ($sortieForm->get('Enregistrer')->isClicked()) {

                    $state = $entityManager->getRepository(Etat::class)->find(1);

                } else if ($sortieForm->get('Publier_la_sortie')->isClicked()) {

                    $state = $entityManager->getRepository(Etat::class)->find(2);
                }

                $sortie->setEtat($state);

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

    #[Route('/sorties/{id}/modifier', name: 'sortie_modifier')]
    public function modifier_sortie(int $id, Sortie $sortie, Request $request) : Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash();

        }

        return $this->render('Main/sortie.modifier.html.twig', [
           'form' => $form->createView()
        ]);

    }

    /*public function annuler_sortie(Request $request, )*/

}