<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Form\Type\UserCsvType;
use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/uploadCsv', 'uploadCsv')]
    public function uploadCsv(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $formCsv = $this->createForm(UserCsvType::class);
        $formManual = $this->createForm(ParticpantType::class);
        $formCsv->handleRequest($request);
        $formManual->handleRequest($request);

        if ($formCsv->isSubmitted() && $formCsv->isValid()) {
            $file = $formCsv->get('csvFile')->getData();
            
            if ($file) {
                $campusRepository = 
                    $entityManager
                    ->getRepository(Campus::class)
                    ->findAll();

                $nameToCampusMap = [];
                foreach ($campusRepository as $campus) {
                    $nameToCampusMap[$campus->getNom()] = $campus;
                }

                $csv = array_map('str_getcsv', file($file->getPathname()));
                $isFirstLine = true;
                $newParticipants = array();
                foreach ($csv as $row) {
                    if ($isFirstLine) {
                        $isFirstLine = false;
                        continue;
                    }

                    $user = $this->parsingCsv($row, $nameToCampusMap, $passwordHasher);
                    $entityManager->persist($user);
                    array_push($newParticipants, $user);
                }
                $entityManager->flush();
            }

            $userIds = array_map(function ($user) {
                return $user->getId();
            }, $newParticipants);
            $idString = implode(',', $userIds);

            return $this->redirectToRoute('admin_success', ['idString' => $idString]);
        }

        return $this->render('admin/add_users.html.twig', [
            'formCsv' => $formCsv->createView(),
            'formManual' => $formManual->createView()
        ]);
    }

    #[Route('/success/{idString}', 'success')]
    public function success($idString, EntityManagerInterface $entityManager): Response
    {
        $idArray = explode(',', $idString);
        $newParticipantRepository = 
            $entityManager
            ->getRepository(Participant::class)
            ->findBy(['id' => $idArray]);

        return $this->render('admin/success.html.twig', [
            'newParticipants' => $newParticipantRepository
        ]);
    }

    private function parsingCsv(
        $row,
        $nameToCampusMap,
        UserPasswordHasherInterface $passwordHasher
    ): Participant {
        $user = new Participant();
        $user->setNom($row[0]);
        $user->setPrenom($row[1]);
        $user->setTelephone($row[2]);
        $user->setMail($row[3]);
        $user->setPseudo($row[4]);

        $plaintextPassword = $row[5];
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setMotPasse($hashedPassword);

        $user->setAdministrateur($row[6] == 'true' ? true : false);
        $user->setActif($row[7] == 'false' ? false : true);
        $user->setCampus($nameToCampusMap[$row[8]]);

        return $user;
    }
}
