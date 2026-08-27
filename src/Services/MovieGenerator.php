<?php

namespace App\Services;

use App\Model\Movie;
use App\Model\Trailer;

class MovieGenerator
{

    public function generate(string $seed, int $pageNumber)
    {

        $faker = \Faker\Factory::create('en_US');
        $faker->addProvider(new \Faker\Provider\TitleFaker($faker));
        $faker->seed($seed);

        $movies = [];

        for ($i = 1; $i <= 15 + (15 * $pageNumber); $i++) {
            array_push($movies, new Movie(

                $i,
                $faker->title(),
                [$faker->name()],
                $faker->name(),
                $faker->numberBetween(2020, 2026),
                $faker->words(1, true),
                new Trailer(
                    strval($faker->numberBetween(1, 5)),
                    strval($faker->numberBetween(1, 5)),
                    $faker->numberBetween(1, 9),
                    $faker->numberBetween(1, 9),
                    $faker->numberBetween(1, 9),
                    $faker->numberBetween(10, 15),
                ),
                $faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2)
            ));
        }

        return $movies;
    }
}
