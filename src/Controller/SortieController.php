<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulationSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    #[Route('/sorties/creation', name: 'sortie_creation')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $townRepository = $entityManager->getRepository(Ville::class)->findAll();
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            /** @var Participant $user */
            $user = $this->getUser();
            $sortie->setOrganisateur($user);
            $sortie->addParticipant($user);

            $campus = $user->getCampus();
            $sortie->setCampus($campus);

            /** @var ClickableInterface $buttonEnregistrer  */
            $buttonEnregistrer = $sortieForm->get('Enregistrer');
            if ($buttonEnregistrer->isClicked()) {

                $state = $entityManager->getRepository(Etat::class)->findOneByLabel(Etat::CREEE);
            }

            /** @var ClickableInterface $buttonPublier  */
            $buttonPublier = $sortieForm->get('Publier_la_sortie');
            if ($buttonPublier->isClicked()) {

                $state = $entityManager->getRepository(Etat::class)->findOneByLabel(Etat::OUVERTE);
            }

            $sortie->setEtat($state);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créée');
            return $this->redirectToRoute('sortie_infos', ['id' => $sortie->getId()]);
        }

        return $this->render('Main/sortie.create.html.twig', [
            'towns' => $townRepository,
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    #[Route('/sorties/infos/{id}', name: 'sortie_infos')]
    public function infos_sortie(SortieRepository $sortieRepository, int $id): Response
    {
        $sortie = $sortieRepository->find($id);


        return $this->render(
            'Main/sortie.afficher.html.twig',
            [
                "sortie" => $sortie
            ]
        );
    }

    #[Route('/sorties/{id}/modifier', name: 'sortie_modifier')]
    public function modifier_sortie(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $modifierForm = $this->createForm(SortieType::class, $sortie);

        $modifierForm->handleRequest($request);

        if ($modifierForm->isSubmitted() && $modifierForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash(type: 'success', message: 'La sortie a été modifiée avec succès');
            return $this->redirectToRoute('list_main');
        }

        return $this->render('Main/sortie.modifier.html.twig', [
            'modifierForm' => $modifierForm->createView(),
            'sortie' => $sortie
        ]);
    }

    #[Route('/sorties/{id}/annuler', name: 'sortie_annuler')]
    public function annuler_sortie(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $annulerForm = $this->createForm(AnnulationSortieType::class, $sortie);
        $annulerForm->handleRequest($request);

        if ($annulerForm->isSubmitted() && $annulerForm->isValid()) {

            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash(type: 'success', message: 'La sortie a été annulée avec succès');
            return $this->redirectToRoute('list_main');
        }

        return $this->render('Main/sortie.supprimer.html.twig', [
            'modifierForm' => $annulerForm->createView(),
            'sortie' => $sortie
        ]);
    }

    #[Route('/sorties/{id}/publication', name: 'sortie_publication')]
    public function publication(int $id, EntityManagerInterface $entityManager): Response
    {
        $state = $entityManager->getRepository(Etat::class)->findOneByLabel(Etat::OUVERTE);
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $sortie->setEtat($state);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash(type: 'success', message: 'La sortie a été publiée avec succès');
        return $this->redirectToRoute('list_main');
    }

    #[Route('/sorties/get-places/{id}', 'sortie_get-places')]
    public function get_places($id, EntityManagerInterface $entityManager): JsonResponse
    {
        $places = $entityManager->getRepository(Lieu::class)->findBy(['ville' => $id]);

        $placesArray = [];
        foreach ($places as $place) {
            $placesArray[] = [
                'id' => $place->getId(),
                'nom' => $place->getNom(),
            ];
        }

        return new JsonResponse($placesArray);
    }
}
