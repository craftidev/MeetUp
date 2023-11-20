<?php

namespace App\Controller;

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
        $filters = $request->request->all();
        $sortiesFilterForm = $this-> createForm(SortiesFilterType::class, $filters);
        $sortiesFilterForm->handleRequest($request);

        $user = $this->getUser();
        $today = new \DateTime;

        if ($request->isMethod('POST')) {
            $sorties = $this->$sortieRepository->findSortiesWithFilters($filters);
        } else {
            $sorties = $sortieRepository->findBy([], ['dateHeureDebut' => 'DESC']);
        }
        
        return $this->render('temp/list.html.twig', [
            'user' => $user,
            'sorties' => $sorties,
            'today' => $today
        ]);
    }
}
