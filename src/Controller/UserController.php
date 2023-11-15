<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'monprofil')]
    public function monprofil(): Response
    {
        return $this->render('user/monprofil.html.twig', [
        ]);
    }

    #[Route('/modifprofil', name: 'modifprofil')]
    public function modifprofil(Request $request): Response
    {

        return $this->render('user/modifprofil.html.twig', ['userForm' => $serieForm->createView()
        ]);
    }
}