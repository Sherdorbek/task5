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
        $reviewAmount = floatval($request->query->get('amount') ?: 0);
        $locale = $request->getSession()->get('locale') ?? 'en_US';

        $request->getSession()->set('review_rate', $reviewAmount);

        $reply = $review->generate(
            $request->getSession()->get('seed') ?? '12345678',
            $page,
            $reviewAmount,
            $locale
        );

        return $this->json($reply);
    }
}
