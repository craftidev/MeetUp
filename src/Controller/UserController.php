<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'monprofil')]
    public function monprofil(): Response
    {
        return $this->render('user/monprofil.html.twig', [
        ]);
    }

    #[Route('/editprofil', name: 'editprofil')]
    public function editprofil(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form -> handleRequest($request);

        return $this->render('user/modifprofil.html.twig', ['userForm' => $form->createView()
       ]);
    }
}
