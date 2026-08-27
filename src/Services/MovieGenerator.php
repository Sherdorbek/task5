<?php

namespace App\Services;

use App\Model\Movie;
use App\Model\Trailer;
use App\Services\TitleFaker;
use App\Services\Genre;

class MovieGenerator
{

    public function generate(string $seed, int $pageNumber,string $locale)
    {

        $faker = \Faker\Factory::create($locale);
        $faker->addProvider(new TitleFaker($faker,$locale));
        $faker->seed($seed);


        $movies = [];

        for ($i = 1; $i <= 15 + (15 * $pageNumber); $i++) {
            array_push($movies, new Movie(

                $i,
                $faker->title(),
                $faker->name().', '.$faker->name().', '.$faker->name(),
                $faker->name(),
                $faker->numberBetween(2020, 2026),
                Genre::get($locale,$faker->numberBetween(0,12)),
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
