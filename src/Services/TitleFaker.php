<?php
namespace Faker\Provider;

class TitleFaker extends \Faker\Provider\Base
{
    protected static $nouns = [];
    protected static $adjs = [];
    protected static $loaded = false;

    protected static function loadWords()
    {
        if (static::$loaded) {
            return;
        }

        static::$nouns = file( __DIR__ . '/../words/nouns.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        static::$adjs = file(__DIR__ . '/../words/adjectives.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        static::$loaded = true;
    }

    public function title()
    {
        static::loadWords();

        $adj = static::randomElement(static::$adjs);
        $noun = static::randomElement(static::$nouns);

        return ucfirst($adj." ".$noun);
    }

}
