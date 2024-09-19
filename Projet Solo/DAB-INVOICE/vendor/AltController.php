<?php

// src/Controller/AltController/php
namespace App\Controller

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AltController extends AbstractController
{
  /**
    * @Route("/alt/home", name="homepage_alt")
    */
    public function index(): Response 
    {
        return $this->render('home/index_alt.html.twig');
    }

  /**
    * @Route("/alt/clients", name="client_list_alt")
    */
    public function listClients(): Response 
    {
        $clients = []; // remplacer la récupération réelle des clients 
        return $this->render('client/list_alt.html.twig', [
            'clients' => $clients,
        ]);
    }

    // Ajouter d'autres routes pour les autres vues
}
