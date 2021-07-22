<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 * @UniqueEntity("titre")
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Positive
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $note;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero
     */
    private $ageMinimal;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity=Acteur::class, inversedBy="films")
     */
    private $acteur;

    public function __construct()
    {
        $this->acteur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAgeMinimal(): ?int
    {
        return $this->ageMinimal;
    }

    public function setAgeMinimal(int $ageMinimal): self
    {
        $this->ageMinimal = $ageMinimal;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Acteur[]
     */
    public function getActeur(): Collection
    {
        return $this->acteur;
    }

    public function addActeur(Acteur $acteur): self
    {
        if (!$this->acteur->contains($acteur)) {
            $this->acteur[] = $acteur;
        }

        return $this;
    }

    public function removeActeur(Acteur $acteur): self
    {
        $this->acteur->removeElement($acteur);

        return $this;
    }

    public function __toString()
    {
        return $this->getTitre();
    }
}
