<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Service\Client\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class ClientController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack,
        private readonly RouterInterface $router,
        private readonly Environment $twig,
        private readonly ClientService $clientService
    ) {
    }

    #[Route('/admin/clients/lister', name: 'admin_client_list')]
    public function list(): Response
    {
        $clients = $this->em->getRepository(Client::class)->findBy(['archivedAt' => null]);

        // Débogage : Afficher les clients dans la Console du navigateur
        dump($clients);

        return $this->render('client/list.html.twig', [
            'title' => 'Liste des Clients',
            'clients' => $clients,
        ]);
    }

    #[Route('/admin/clients/archive', name: 'admin_client_archived')]
    public function archived(): Response
    {
        $clients = $this->em->getRepository(Client::class)
            ->createQueryBuilder('c')
            ->where('c.archivedAt IS NOT NULL')
            ->getQuery()
            ->getResult();

        // Débogage : Afficher les clients dans la Console du navigateur
        dump($clients);

        return $this->render('client/archived.html.twig', [
            'title' => 'Liste des Clients Archivés',
            'clients' => $clients
        ]);
    }


    #[Route('/admin/clients/creer', name: 'admin_client_create')]
    #[Route('/admin/clients/editer/{id}', name: 'admin_client_edit')]
    public function createOrEdit(Client $client = null, Request $request): Response
    {
        if (!$client) {
            $client = new Client();
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($client);
            $this->em->flush();

            return $this->redirectToRoute('admin_client_list');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'title' => $this->em->contains($client) ? 'Modifier le Client' : 'Créer un Client',
        ]);
    }

    #[Route('/admin/clients/archive/{id}', name: 'admin_client_archive', methods: ['PUT'])]
    public function archive(Client $client, Request $request): JsonResponse
    {
        if ($client->getArchivedAt()) {
            return $this->json(['error' => 'Client already archived'], 400);
        }

        $client->setArchivedAt(new \DateTime());
        $this->em->flush();

        return $this->json(['success' => 'Client archived successfully']);
    }

    #[Route('/admin/clients/restaurer/{id}', name: 'admin_client_restore', methods: ['POST'])]
    public function restore(Client $client, Request $request): JsonResponse
    {
        if (!$client->getArchivedAt()) {
            return $this->json(['error' => 'Client is not archived'], 400);
        }

        $client->setArchivedAt(null);
        $this->em->flush();

        return $this->json(['success' => 'Client restored successfully']);
    }
}
