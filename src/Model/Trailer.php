<?php

namespace App\Model;

class Trailer
{

    public function __construct(
        private string $video1,
        private string $video2,
        private string $intro,
        private string $outro,
        private int $colorEffect,
        private int $zoom,
    ) {}


    public function getVideo1(): string
    {
        return $this->video1;
    }
    public function getVideo2(): string
    {
        return $this->video2;
    }
    public function getIntro(): string
    {
        return $this->intro;
    }
    public function getOutro(): string
    {
        return $this->outro;
    }
    public function getColorEffect(): int
    {
        return $this->colorEffect;
    }
    public function getZoom(): int
    {
        return $this->zoom;
    }
}
