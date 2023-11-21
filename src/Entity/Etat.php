<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
#[UniqueEntity(fields:['libelle'])]
class Etat
{

    public const CREEE = 'Créée';
    public const OUVERTE = 'Ouverte';
    public const CLOTUREE = 'Clôturée';
    public const ACTIVITE_EN_COURS = 'Activité en cours';
    public const PASSEE = 'Passée';
    public const ANNULEE = 'Annulée';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, unique: true)]
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
