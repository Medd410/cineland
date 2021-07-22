<?php

namespace App\Entity;

class FilmSearch
{
    /**
     * @var string|null
     */
    private $titre;

    /**
     * @var int|null
     */
    private $anneeMin;

    /**
     * @var int|null
     */
    private $anneeMax;

    /**
     * @var \DateTimeInterface|null
     */
    private $dateMax;

    /**
     * @var Acteur|null
     */
    private $acteurUn;

    /**
     * @var Acteur|null
     */
    private $acteurDeux;

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return Acteur|null
     */
    public function getActeurUn(): ?Acteur
    {
        return $this->acteurUn;
    }

    /**
     * @param Acteur|null $acteurUn
     */
    public function setActeurUn(?Acteur $acteurUn): void
    {
        $this->acteurUn = $acteurUn;
    }

    /**
     * @return Acteur|null
     */
    public function getActeurDeux(): ?Acteur
    {
        return $this->acteurDeux;
    }

    /**
     * @param Acteur|null $acteurDeux
     */
    public function setActeurDeux(?Acteur $acteurDeux): void
    {
        $this->acteurDeux = $acteurDeux;
    }

    /**
     * @return int|null
     */
    public function getAnneeMin(): ?int
    {
        return $this->anneeMin;
    }

    /**
     * @param int|null $anneeMin
     */
    public function setAnneeMin(?int $anneeMin): void
    {
        $this->anneeMin = $anneeMin;
    }

    /**
     * @return int|null
     */
    public function getAnneeMax(): ?int
    {
        return $this->anneeMax;
    }

    /**
     * @param int|null $anneeMax
     */
    public function setAnneeMax(?int $anneeMax): void
    {
        $this->anneeMax = $anneeMax;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateMax(): ?\DateTimeInterface
    {
        return $this->dateMax;
    }

    /**
     * @param \DateTimeInterface|null $dateMax
     */
    public function setDateMax(?\DateTimeInterface $dateMax): void
    {
        $this->dateMax = $dateMax;
    }
}