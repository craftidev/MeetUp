<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $campusLapins = new Campus();
        $campusLapins->setNom('Lapins');
        $manager->persist($campusLapins);

        $campusSerpents = new Campus();
        $campusSerpents->setNom('Serpents');
        $manager->persist($campusSerpents);

        $etatCreer = new Etat();
        $etatCreer->setLibelle('Créée');
        $manager->persist($etatCreer);
        
        $etatOuverte = new Etat();
        $etatOuverte->setLibelle('Ouverte');
        $manager->persist($etatOuverte);
        
        $etatCloturee = new Etat();
        $etatCloturee->setLibelle('Clôturée');
        $manager->persist($etatCloturee);
        
        $etatActiviteEnCours = new Etat();
        $etatActiviteEnCours
            ->setLibelle('Activité en cours');
        $manager->persist($etatActiviteEnCours);
        
        $etatPassee = new Etat();
        $etatPassee->setLibelle('Passée');
        $manager->persist($etatPassee);
        
        $etatAnnulee = new Etat();
        $etatAnnulee->setLibelle('Annulée');
        $manager->persist($etatAnnulee);

        $paris = new Ville();
        $paris->setNom('Paris 7eme');
        $paris->setCodePostal('75007');
        $manager->persist($paris);

        $lyon = new Ville();
        $lyon->setNom('Lyon 6eme');
        $lyon->setCodePostal('69006');
        $manager->persist($lyon);

        $eiffel = new Lieu();
        $eiffel->setNom('Tour Eiffel');
        $eiffel->setRue('Champ de Mars, 5 Av. Anatole France');
        $eiffel->setVille($paris);
        $manager->persist($eiffel);

        $tetedor = new Lieu();
        $tetedor->setNom('Parc de la tete d\'Or');
        $tetedor->setRue('Place Général Leclerc');
        $tetedor->setVille($lyon);
        $manager->persist($tetedor);

        $mairie = new Lieu();
        $mairie->setNom('Mairie');
        $mairie->setRue('58 Rue de Sèze');
        $mairie->setVille($lyon);
        $manager->persist($mairie);

        $gatsby = new Lieu();
        $gatsby->setNom('Bar Gatsby');
        $gatsby->setRue('64 Avenue Bosquet');
        $gatsby->setVille($paris);
        $manager->persist($gatsby);

        $participantAdmin = new Participant();
        $participantAdmin->setNom('adminAccount');
        $participantAdmin->setPrenom('admin');
        $participantAdmin->setTelephone('0000000001');
        $participantAdmin->setMail('admin@sorties.com');
        $participantAdmin->setPseudo('Administrateur');
        $participantAdmin->setAdministrateur(true);
        $participantAdmin->setActif(true);
        $participantAdmin->setCampus($campusLapins);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participantAdmin,
            'Administrateur'
        );
        $participantAdmin->setMotPasse($hashedPassword);
        $manager->persist($participantAdmin);

        $olivia = new Participant();
        $olivia->setNom('Serenelli-Pesin');
        $olivia->setPrenom('Olivia');
        $olivia->setTelephone('0611223344');
        $olivia->setMail('olivia@sorties.com');
        $olivia->setPseudo('Olivia');
        $olivia->setAdministrateur(false);
        $olivia->setActif(true);
        $olivia->setCampus($campusLapins);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $olivia,
            'Olivia'
        );
        $olivia->setMotPasse($hashedPassword);
        $manager->persist($olivia);

        $noemie = new Participant();
        $noemie->setNom('Lameyse');
        $noemie->setPrenom('Noémie');
        $noemie->setTelephone('0711223344');
        $noemie->setMail('noemie@sorties.com');
        $noemie->setPseudo('Noémie');
        $noemie->setAdministrateur(false);
        $noemie->setActif(true);
        $noemie->setCampus($campusLapins);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $noemie,
            'Noémie'
        );
        $noemie->setMotPasse($hashedPassword);
        $manager->persist($noemie);

        $ivann = new Participant();
        $ivann->setNom('Dubois');
        $ivann->setPrenom('Ivann');
        $ivann->setTelephone('0311223344');
        $ivann->setMail('ivann@sorties.com');
        $ivann->setPseudo('Ivann');
        $ivann->setAdministrateur(false);
        $ivann->setActif(true);
        $ivann->setCampus($campusSerpents);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $ivann,
            'Ivann'
        );
        $ivann->setMotPasse($hashedPassword);
        $manager->persist($ivann);

        $sortie1 = new Sortie();
        $sortie1->setNom('Bar Gasby');
        $date = new \DateTime();
        $sortie1->setDateHeureDebut($date->modify('+1 week')->modify('-19292 seconds'));
        $sortie1->setDuree(90);
        $date = new \DateTime();
        $sortie1->setDateLimiteInscription($date->modify('+3 days')->modify('-22929 seconds'));
        $sortie1->setNbInscriptionsMax(5);
        $sortie1->setInfosSortie('Boire un coup dans la bonne ambiance, interdit de parler boulot.');
        $sortie1->setOrganisateur($olivia);
        $sortie1->addParticipant($olivia);
        $sortie1->addParticipant($noemie);
        $sortie1->setEtat($etatOuverte);
        $sortie1->setCampus($campusLapins);
        $sortie1->setLieu($gatsby);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom('Fin de projet PHP');
        $date = new \DateTime();
        $sortie2->setDateHeureDebut($date->modify('+1 days')->modify('-3999 seconds'));
        $sortie2->setDuree(45);
        $date = new \DateTime();
        $sortie2->setDateLimiteInscription($date->modify('-1 days')->modify('-49999 seconds'));
        $sortie2->setNbInscriptionsMax(3);
        $sortie2->setInfosSortie('Celebration d\'un projet reussit !');
        $sortie2->setOrganisateur($ivann);
        $sortie2->addParticipant($ivann);
        $sortie2->addParticipant($olivia);
        $sortie2->addParticipant($noemie);
        $sortie2->setEtat($etatCloturee);
        $sortie2->setCampus($campusSerpents);
        $sortie2->setLieu($eiffel);
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom('Promenade le long des quais');
        $date = new \DateTime();
        $sortie3->setDateHeureDebut($date->modify('-10 days')->modify('-59899 seconds'));
        $sortie3->setDuree(85);
        $date = new \DateTime();
        $sortie3->setDateLimiteInscription($date->modify('-11 days')->modify('-69989 seconds'));
        $sortie3->setNbInscriptionsMax(30);
        $sortie3->setInfosSortie('On se retrouve a la mairie.');
        $sortie3->setOrganisateur($noemie);
        $sortie3->addParticipant($noemie);
        $sortie3->addParticipant($ivann);
        $sortie3->setEtat($etatCloturee);
        $sortie3->setCampus($campusSerpents);
        $sortie3->setLieu($mairie);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom('Sortie au zoo');
        $date = new \DateTime();
        $sortie4->setDateHeureDebut($date->modify('+21 days')->modify('-71919 seconds'));
        $sortie4->setDuree(85);
        $date = new \DateTime();
        $sortie4->setDateLimiteInscription($date->modify('+13 days')->modify('-89191 seconds'));
        $sortie4->setNbInscriptionsMax(9);
        $sortie4->setInfosSortie('On peut promener nos animaux. Ne loupez pas les jardins.');
        $sortie4->setOrganisateur($olivia);
        $sortie4->addParticipant($noemie);
        $sortie4->addParticipant($olivia);
        $sortie4->setEtat($etatOuverte);
        $sortie4->setCampus($campusLapins);
        $sortie4->setLieu($tetedor);
        $manager->persist($sortie4);

        $manager->flush();
    }
}
