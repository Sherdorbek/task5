<?php

namespace App\Controller;

use App\Services\MovieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

final class ProductController extends AbstractController
{

    #[Route('/products', name: 'product_list')]
    public function index(MovieGenerator $generator, PaginatorInterface $paginator, Request $request): Response
    {
        $seed = $request->getSession()->get('seed') ?? '12345678';
        $view = $request->getSession()->get('view', 'table');
        $pageFrom = max(1, $request->query->getInt('page') ?? 1);

        if ($seed !== '12345678')
            $request->getSession()->set('seed', $seed);

        $all = $generator->generate($seed, $pageFrom);
        if ($view === 'table'){
        $movies = $paginator->paginate(
            $all,
            $pageFrom,
            15 // items per page
        );}
        else{
            $movies=$all;
        }

        return $this->render('product/index.html.twig', [
            'movies' => $movies,
            'seed' => $seed,
            'view' => $view,
            'page'=> $pageFrom
        ]);
    }


    #[Route('/products/change/seed', name: 'product_seed', methods: ['POST'])]
    public function update_seed(Request $request): Response
    {
        $newSeed = $request->request->get('seed');
        $request->getSession()->set('seed', $newSeed);

        return $this->redirectToRoute('product_list');
    }

    #[Route('/products/change/view', name: 'product_view', methods: ['POST'])]
    public function view(Request $request): Response
    {
        $newSeed = $request->request->get('view');
        $request->getSession()->set('view', $newSeed);

        return $this->redirectToRoute('product_list');
    }
}
