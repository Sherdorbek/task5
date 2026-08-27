<?php

namespace App\Controller;

use App\Services\ReviewGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewApiController extends AbstractController
{
    #[Route('/api/review/{page<\d+>}', name: 'api_review', methods: ["get"])]
    public function index(int $page, ReviewGenerator $review, Request $request): Response
    {
        $reply = $review->generate(
            $request->getSession()->get('seed') ?? '12345678',
            $page,
            $request->query->get('amount')
        );

        return $this->json($reply);
    }
}
