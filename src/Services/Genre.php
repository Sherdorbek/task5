<?php

namespace App\Services;

class Genre
{
    private const EN = [
        'Action',
        'Comedy',
        'Drama',
        'Horror',
        'Romance',
        'Sci-Fi',
        'Thriller',
        'Animation',
        'Adventure',
        'Fantasy',
        'Crime',
        'Documentary',
        'Mystery'
    ];

    private const ES = [
        'Acción',
        'Comedia',
        'Drama',
        'Terror',
        'Romance',
        'Ciencia Ficción',
        'Suspenso',
        'Animación',
        'Aventura',
        'Fantasía',
        'Crimen',
        'Documental',
        'Misterio'
    ];

    public static function get(string $locale, int $index): string
    {
        $list = ($locale === 'es_ES') ? self::ES : self::EN;
        return $list[$index];
    }

}
