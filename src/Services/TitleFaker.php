<?php


namespace App\Services;

use Faker\Generator;

class TitleFaker extends \Faker\Provider\Base
{
    protected static $nouns = [];
    protected static $adjs = [];
    protected static array $loadedLocales = [];
    private string $locale;

    public function __construct(Generator $generator, string $locale)
    {
        parent::__construct($generator);
        $this->locale = $locale;
    }

    protected function loadWords(string $locale): void
    {
        if (isset(static::$loadedLocales[$locale])) {
            return;
        }

        $nounsPath = __DIR__ . "/../words/{$locale}/nouns.txt";
        $adjsPath  = __DIR__ . "/../words/{$locale}/adjectives.txt";

        if (!file_exists($nounsPath) || !file_exists($adjsPath)) {
            $nounsPath = __DIR__ . '/../words/en_US/nouns.txt';
            $adjsPath  = __DIR__ . '/../words/en_US/adjectives.txt';
        }

        static::$nouns[$locale] = file($nounsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        static::$adjs[$locale]  = file($adjsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        static::$loadedLocales[$locale] = true;
    }


    public function title()
    {
        $this->loadWords($this->locale);

        $adj = static::randomElement(static::$adjs[$this->locale]);
        $noun = static::randomElement(static::$nouns[$this->locale]);

        return ucfirst($adj . " " . $noun);
    }
}
