<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CommandRepository;

class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     */
    public function index(CommandRepository $repo): Response
    {
        $listCommand = $repo->findAll();
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'listCommand' => $listCommand
        ]);
    }

    /**
     * @Route("/command/{id}", name="command.show")
     */
    public function show($id, CommandRepository $repo): Response
    {
        $command = $repo->find($id);
        if (!$command) {
            throw $this->createNotFoundException("La commande n'existe pas");
        } else {
            return $this->render('command/show.html.twig', [
                'controller_name' => 'commandController',
                'command' => $command,
                'listProducts' => $command->getProducts()
            ]);
        }
    }
}
