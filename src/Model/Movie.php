<?php

namespace App\Model;

class Movie
{

    public function __construct(
        private int $index,
        private string $title,
        private string $actors,
        private string $director,
        private int $year,
        private string $genre,
        private Trailer $trailer,
        private string $description,
    ) {}


    public function getIndex(): int
    {
        return $this->index;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getActors(): string
    {
        return $this->actors;
    }
    public function getDirector(): string
    {
        return $this->director;
    }
    public function getYear(): int
    {
        return $this->year;
    }
    public function getGenre(): string
    {
        return $this->genre;
    }
    public function getTrailer(): Trailer
    {
        return $this->trailer;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
}
