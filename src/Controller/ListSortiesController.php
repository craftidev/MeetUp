<?php

namespace App\Controller;

use App\DTO\SortiesFilterDTO;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortiesFilterType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

// TODO refactor with Sortie controller maybe
#[Route('/list', name: 'list_')]
class ListSortiesController extends AbstractController
{
    #[Route('', name: 'main')]
    public function main(SortieRepository $sortieRepository, Request $request): Response
    {
        $filters = new SortiesFilterDTO();
        $sortiesFilterForm = $this->createForm(SortiesFilterType::class, $filters);
        $sortiesFilterForm->handleRequest($request);
        
        /** @var Participant $user */
        $user = $this->getUser();
        
        if ($sortiesFilterForm->isSubmitted() && $sortiesFilterForm->isValid()) {
            $userId = $user->getId();
            
            $sorties = $sortieRepository->findSortiesWithFilters($filters, $userId);
        } else {
            $sorties = $sortieRepository->findBy([], ['dateHeureDebut' => 'DESC']);
        }
        
        $today = new \DateTime;
        return $this->render('temp/list.html.twig', [
            'user' => $user,
            'sorties' => $sorties,
            'today' => $today,
            'sortiesFilterForm' => $sortiesFilterForm
        ]);
    }
}
