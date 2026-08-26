<?php

namespace App\Model;

class Movie
{

    public function __construct(
        private int $index,
        private string $title,
        private array $actors,
        private string $director,
        private int $year,
        private string $genre,
        private string $trailer,
        private array $reviews,
        private int $likes,
    ) {}


    public function getIndex(): int
    {
        return $this->index;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getActors(): array
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
    public function getTrailer(): string
    {
        return $this->trailer;
    }
    public function getReviews(): array
    {
        return $this->reviews;
    }
    public function getLikes(): int
    {
        return $this->likes;
    }
}
