<?php

namespace App\Services;

use App\Model\Review;

class ReviewGenerator
{

    public function generate(string $seed, int $pageNumber, float $amount, string $locale)
    {

        $faker = \Faker\Factory::create($locale);
        $faker->seed("$seed$pageNumber");

        $estimate = [];
        $amountInt = floor($amount);
        $amountFloat = 10 * ($amount - $amountInt);

        for ($i = 0; $i < 10; $i++) {
            if ($amountFloat >= 1) {
                array_push($estimate, $amountInt + 1);
                $amountFloat--;
            } else {
                array_push($estimate, $amountInt);
            }
        }

        $reviews = [];
        for ($i = 0; $i < $pageNumber * 15 + 30; $i++) {
            $review = [];
            for ($j = 1; $j <= $faker->randomElement($estimate); $j++) {
                array_push($review, new Review(
                    $faker->name(),
                    $faker->realText(80, 2)
                ));
            }
            array_push($reviews, $review);
        }



        return $reviews;
    }
}
