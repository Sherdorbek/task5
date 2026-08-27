<?php

namespace App\Services;

enum GenreEnum: string
{
    case ACTION = 'Action';
    case COMEDY = 'Comedy';
    case DRAMA = 'Drama';
    case HORROR = 'Horror';
    case ROMANCE = 'Romance';
    case SCI_FI = 'Sci-Fi';
    case THRILLER = 'Thriller';
    case ANIMATION = 'Animation';
    case ADVENTURE = 'Adventure';
    case FANTASY = 'Fantasy';
    case CRIME = 'Crime';
    case DOCUMENTARY = 'Documentary';
    case MYSTERY = 'Mystery';

    public static function choose(int $num): self
    {
        $cases = self::cases();
        return $cases[$num];
    }
}
