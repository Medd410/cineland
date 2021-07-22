<?php

namespace App\Entity;

class GenreSearch
{

    /**
     * @var Acteur|null
     */
    private $acteur;

    /**
     * @return Acteur|null
     */
    public function getActeur(): ?Acteur
    {
        return $this->acteur;
    }

    /**
     * @param Acteur|null $acteur
     */
    public function setActeur(?Acteur $acteur): void
    {
        $this->acteur = $acteur;
    }
}