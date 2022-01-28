<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Command;
use App\Form\CommandType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart.show")
     */
    public function index(SessionInterface $session, ProductRepository $repo, ManagerRegistry $doct): Response
    {
        $cart = $session->get('panier', []);
        $products = [];
        $total = 0;
        $command = new Command();
        $em = $doct->getManager();
        foreach($cart as $id => $quantity)
        {
            $products[] = $repo->find($id);
            $total += $repo->find($id)->getPrice() * $quantity;
            $command->addProduct($repo->find($id));
        }

        $commandform = $this->createForm(CommandType::class, $command);
        $request = Request::createFromGlobals();
        $commandform->handleRequest($request);
        
        if ($commandform->isSubmitted()) { 
            $command->setCreatedAt(new \DateTime);
            $em->persist($command);
            $em->flush();
            return $this->redirectToRoute('cart.show');
        }

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'products' => $products,
            'total' => $total,
            'form' => $commandform->createView()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     */
    public function add($id, SessionInterface $session, ProductRepository $repo): Response
    {
        $product = $repo->find($id);
        if($product){
            $cart = $session->get('panier', []);
            // ...
            $cart[$id] = 1;
            // ...
            $session->set('panier', $cart);
            // ...
            $products = [];
            return $this->redirectToRoute('cart.show');
        }else{
            throw $this->createNotFoundException('nok');
        }
        
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     */
    public function delete($id, SessionInterface $session, ProductRepository $repo): Response
    {
        $product = $repo->find($id);
        if($product){
            $cart = $session->get('panier', []);
            // ...
            unset($cart[$id]);
            // ...
            $session->set('panier', $cart);
            // ...
            $cart = $session->get('panier', []);
            $products = [];
            $total = 0;
            foreach($cart as $id => $quantity)
            {
                $products[] = $repo->find($id);
                $total += $repo->find($id)->getPrice() * $quantity;
            }
            return $this->render('cart/show.html.twig', [
                'cart' => $cart,
                'products' => $products,
                'total' => $total
            ]);
        }else{
            throw $this->createNotFoundException('nok');
        }
        
    }
}
