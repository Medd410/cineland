<?php

namespace App\Entity;

class ActeurSearch
{
    /**
     * @var Film|null
     */
    private $film;

    /**
     * @var int|null
     */
    private $filmMin;

    /**
     * @return Film|null
     */
    public function getFilm(): ?Film
    {
        return $this->film;
    }

    /**
     * @param Film|null $film
     */
    public function setFilm(?Film $film): void
    {
        $this->film = $film;
    }

    /**
     * @return int|null
     */
    public function getFilmMin(): ?int
    {
        return $this->filmMin;
    }

    /**
     * @param int|null $filmMin
     */
    public function setFilmMin(?int $filmMin): void
    {
        $this->filmMin = $filmMin;
    }
}