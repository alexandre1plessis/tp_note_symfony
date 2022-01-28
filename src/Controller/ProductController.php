<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product.index")
     */
    // public function index(ProductRepository $productRepository)
    // public function index(ProductRepository $productRepository, PaginatorInterface $paginator)
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}", name="product.show")
     */
    public function show(int $id, ProductRepository $productRepository) {
        $product = $productRepository->find($id);
        if (!$product)
        {
            throw $this->createNotFoundException('The product does not exist');
        }else {
            return $this->render('product/show.html.twig', [
                'controller_name' => 'ProductController',
                'product' => $product
            ]);
        }
        
    }
    
}
