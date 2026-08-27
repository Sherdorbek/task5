<?php

namespace App\Controller;

use App\Services\LikeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LikeApiController extends AbstractController
{
    #[Route('/api/like/{page<\d+>}', name: 'api_like', methods: ["get"])]
    public function index(int $page, LikeGenerator $likes, Request $request): Response
    {
        $likeAmount = floatval($request->query->get('range') ?: 0);

        $request->getSession()->set('like_rate', $likeAmount);

        $reply = $likes->generate(
            $request->getSession()->get('seed') ?? '12345678',
            $page,
            $likeAmount
        );

        return $this->json($reply);
    }
}
