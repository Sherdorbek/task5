<?php

namespace App\Services;

use App\Model\Movie;

class MovieGenerator
{

    public function generate(string $seed, int $pageNumber)
    {

        $faker = \Faker\Factory::create('en_US');
        $faker->seed($seed . strval($pageNumber));

        $movies = [];
        for ($i = 1; $i <= 15+(15*$pageNumber); $i++) {
            array_push($movies, new Movie(

                $i,
                $faker->catchPhrase(),
                [$faker->name()],
                $faker->name(),
                $faker->numberBetween(2020, 2026),
                $faker->words(1, true),
                $faker->words(1, true),
                [$faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2)],
                $faker->randomDigit()
            ));
        }

        return $movies;
    }
}
