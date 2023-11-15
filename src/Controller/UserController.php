<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('', name: 'modifprofil')]
    public function modifprofil(): Response
    {
        return $this->render('user/modifprofil.html.twig', [
        ]);
    }
}
