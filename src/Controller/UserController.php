<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
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
    #[Route('/viewprofil/{UserId}', name: 'viewprofil')]
    public function viewprofil(Participantrepository $ParticipantRepository, $UserId, Request $request): Response
    {
        $referer = $request->headers->get('referer');

        $user = $ParticipantRepository->find($UserId);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour cet ID');
        }

        return $this->render('user/viewprofil.html.twig', [
            'userId' => $user,
            'referer' => $referer,
        ]);
    }

    #[Route('/testlink/', name: 'testlink')]
    public function testlink(): Response
    {

        return $this->render('temp/testlink.html.twig', [
        ]);
    }


    #[Route('/modifprofil', name: 'modifprofil')]
    public function modifprofil(Request $request,
                               EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(type:'success', message:'Votre profil a été modifié avec succès');
            return $this->redirectToRoute('list_main');
        }

        return $this->render('user/modifprofil.html.twig', ['userForm' => $form->createView()
       ]);
    }
}
