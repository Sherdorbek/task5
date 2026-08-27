<?php

namespace App\Services;

class LikeGenerator
{

    public function generate(string $seed, int $pageNumber, $range)
    {
        $faker = \Faker\Factory::create();
        $faker->seed($seed);

        $estimate = [];
        $rangeInt = (int) floor($range);
        $rangeFloat = 10 * ($range - $rangeInt);

        for ($i = 0; $i < 10; $i++) {
            if ($rangeFloat >= 1) {
                $estimate[] = $rangeInt + 1;
                $rangeFloat--;
            } else {
                $estimate[] = $rangeInt;
            }
        }

        $likes = [];
        $total = max(30, ($pageNumber + 2) * 15);
        for ($i = 0; $i < $total; $i++) {
            $likes[] = ($range <= 0) ? 0 : $faker->randomElement($estimate);
        }

        return $likes;
    }
}
