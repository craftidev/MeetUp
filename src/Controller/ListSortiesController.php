<?php

namespace App\Controller;

use App\DTO\SortiesFilterDTO;
use App\Entity\Participant;
use App\Form\SortiesFilterType;
use App\Repository\SortieRepository;
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
}
