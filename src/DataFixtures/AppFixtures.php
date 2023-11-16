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
        $campus1 = new Campus();
        $campus1->setNom('campus1');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('campus2');
        $manager->persist($campus2);

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
        $etatActiviteEnCours->setLibelle('Activité en
        cours');
        $manager->persist($etatActiviteEnCours);
        
        $etatPassee = new Etat();
        $etatPassee->setLibelle('Passée');
        $manager->persist($etatPassee);
        
        $etatAnnulee = new Etat();
        $etatAnnulee->setLibelle('Annulée');
        $manager->persist($etatAnnulee);

        $ville1 = new Ville();
        $ville1->setNom('ville1');
        $ville1->setCodePostal('00001');
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('ville2');
        $ville2->setCodePostal('00002');
        $manager->persist($ville2);

        $lieu1 = new Lieu();
        $lieu1->setNom('lieu1');
        $lieu1->setRue('qdgh');
        $lieu1->setVille($ville1);
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('lieu2');
        $lieu2->setRue('fffff');
        $lieu2->setVille($ville1);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('lieu3');
        $lieu3->setRue('dddd');
        $lieu3->setVille($ville2);
        $manager->persist($lieu3);

        $lieu4 = new Lieu();
        $lieu4->setNom('lieu4');
        $lieu4->setRue('hhhhhh');
        $lieu4->setVille($ville2);
        $manager->persist($lieu4);

        $participantAdmin = new Participant();
        $participantAdmin->setNom('super');
        $participantAdmin->setPrenom('admin');
        $participantAdmin->setTelephone('9834752345');
        $participantAdmin->setMail('a@a.a');
        $participantAdmin->setPseudo('a');
        $participantAdmin->setAdministrateur(true);
        $participantAdmin->setActif(true);
        $participantAdmin->setCampus($campus1);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participantAdmin,
            'a'
        );
        $participantAdmin->setMotPasse($hashedPassword);
        $manager->persist($participantAdmin);

        $participant1 = new Participant();
        $participant1->setNom('participant1');
        $participant1->setPrenom('un');
        $participant1->setTelephone('1111752345');
        $participant1->setMail('un@un.un');
        $participant1->setPseudo('un');
        $participant1->setAdministrateur(false);
        $participant1->setActif(true);
        $participant1->setCampus($campus1);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participant1,
            'un'
        );
        $participant1->setMotPasse($hashedPassword);
        $manager->persist($participant1);

        $participant2 = new Participant();
        $participant2->setNom('participant2');
        $participant2->setPrenom('deux');
        $participant2->setTelephone('2222752345');
        $participant2->setMail('deux@deux.deux');
        $participant2->setPseudo('deux');
        $participant2->setAdministrateur(false);
        $participant2->setActif(true);
        $participant2->setCampus($campus1);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participant2,
            'deux'
        );
        $participant2->setMotPasse($hashedPassword);
        $manager->persist($participant2);

        $participant3 = new Participant();
        $participant3->setNom('participant3');
        $participant3->setPrenom('trois');
        $participant3->setTelephone('3333752345');
        $participant3->setMail('trois@trois.trois');
        $participant3->setPseudo('trois');
        $participant3->setAdministrateur(false);
        $participant3->setActif(true);
        $participant3->setCampus($campus2);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participant3,
            'trois'
        );
        $participant3->setMotPasse($hashedPassword);
        $manager->persist($participant3);

        $participant4 = new Participant();
        $participant4->setNom('participant3');
        $participant4->setPrenom('quatre');
        $participant4->setTelephone('4444752345');
        $participant4->setMail('quatre@quatre.quatre');
        $participant4->setPseudo('quatre');
        $participant4->setAdministrateur(false);
        $participant4->setActif(true);
        $participant4->setCampus($campus2);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $participant4,
            'quatre'
        );
        $participant4->setMotPasse($hashedPassword);
        $manager->persist($participant4);

        $sortie1 = new Sortie();
        $sortie1->setNom('sortie1');
        $date = new \DateTime();
        $sortie1->setDateHeureDebut($date->modify('+1 week'));
        $sortie1->setDuree(90);
        $date = new \DateTime();
        $sortie1->setDateLimiteInscription($date->modify('+3 days'));
        $sortie1->setNbInscriptionsMax(2);
        $sortie1->setInfosSortie('description of the Sortie1');
        $sortie1->setOrganisateur($participant1);
        $sortie1->addParticipant($participant1);
        $sortie1->addParticipant($participant2);
        $sortie1->setEtat($etatOuverte);
        $sortie1->setCampus($campus1);
        $sortie1->setLieu($lieu1);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom('sortie2');
        $date = new \DateTime();
        $sortie2->setDateHeureDebut($date->modify('+2 week'));
        $sortie2->setDuree(45);
        $date = new \DateTime();
        $sortie2->setDateLimiteInscription($date->modify('+8 days'));
        $sortie2->setNbInscriptionsMax(3);
        $sortie2->setInfosSortie('description of the Sortie2');
        $sortie2->setOrganisateur($participant3);
        $sortie2->addParticipant($participant3);
        $sortie2->setEtat($etatOuverte);
        $sortie2->setCampus($campus2);
        $sortie2->setLieu($lieu2);
        $manager->persist($sortie2);

        $manager->flush();
    }
}
