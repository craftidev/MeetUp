<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// TODO refactor with Sortie controller maybe
#[Route('/list', name: 'list_')]
class ListSortiesController extends AbstractController
{
    #[Route('', name: 'main')]
    public function main(Security $security, SortieRepository $sortieRepository): Response
    {

        $user = $security->getUser();
        $sorties = $sortieRepository->findBy([], ['dateHeureDebut' => 'DESC']);

        return $this->render('temp/list.html.twig', [
            'user' => $user,
            'sorties' => $sorties,
        ]);
    }
}
