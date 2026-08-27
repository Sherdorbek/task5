<?php

namespace App\Services;

use App\Model\Review;

class ReviewGenerator
{

    public function generate(string $seed, int $pageNumber, float $amount, string $locale)
    {
        $faker = \Faker\Factory::create($locale);
        $faker->seed($seed);

        $estimate = [];
        $amountInt = (int) floor($amount);
        $amountFloat = 10 * ($amount - $amountInt);

        for ($i = 0; $i < 10; $i++) {
            if ($amountFloat >= 1) {
                $estimate[] = $amountInt + 1;
                $amountFloat--;
            } else {
                $estimate[] = $amountInt;
            }
        }

        $reviews = [];
        $total = max(30, ($pageNumber + 2) * 15);
        for ($i = 0; $i < $total; $i++) {
            $review = [];
            $count = ($amount <= 0) ? 0 : $faker->randomElement($estimate);
            for ($j = 1; $j <= $count; $j++) {
                $review[] = new Review(
                    $faker->name(),
                    $faker->realText(80, 2)
                );
            }
            $reviews[] = $review;
        }

        return $reviews;
    }
}
