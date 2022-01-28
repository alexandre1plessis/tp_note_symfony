<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $listDate = $productRepository->findBy([], ['createdAt' => 'DESC'], 5);
        $listPrice = $productRepository->findBy([], ['price' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'listDate' => $listDate,
            'listPrice' => $listPrice,
        ]);
    }
}
