<?php

namespace App\Controller;

use App\Services\MovieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MovieApiController extends AbstractController
{
    #[Route('/api/movie', name: 'api_movie')]
    public function index(MovieGenerator $generator): Response
    {
        $movies = $generator->generate(20);
        return $this->json($movies);
    }
    #[Route('/api/movie/{page<\d+>}', name: 'api_movie_page')]
    public function show(int $page, MovieGenerator $generator): Response
    {
        $movies = $generator->generate(20);
        return $this->json(array_slice($movies,15*($page-1),15));
    }
}
