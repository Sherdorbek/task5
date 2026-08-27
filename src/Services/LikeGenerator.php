<?php

namespace App\Services;

class LikeGenerator
{

    public function generate(string $seed, int $pageNumber,$range)
    {

        $faker = \Faker\Factory::create('en_US');
        $faker->seed($seed . strval($pageNumber));

        $estimate = [];
        $rangeInt= floor($range);
        $rangeFloat = 10*($range - $rangeInt);

        for ($i=0; $i < 10; $i++) {
            if ($rangeFloat >= 1){
                array_push($estimate,$rangeInt+1);
                $rangeFloat--;
            }else{
                array_push($estimate,$rangeInt);
            }
        }

        $likes = [];
        for ($i = 1; $i <= 15; $i++) {
            array_push($likes,$faker->randomElement($estimate));
        }

        return $likes;
    }
}
