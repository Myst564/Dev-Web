<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\HistoriqueDocument; // Import de l'entité HistoriqueDocument
use App\Form\DevisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DevisController extends AbstractController
{
    private $client;

    // Injection du client HTTP pour interagir avec l'API Hive
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    // Route pour le menu Devis (devis_index)
    #[Route('/devis', name: 'devis_index')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupération de tous les devis
        $devis = $em->getRepository(Devis::class)->findAll();

        // Renvoi vers le template de la page de devis
        return $this->render('devis/index.html.twig', [
            'title' => 'Devis',
            'devis' => $devis,
        ]);
    }

    // Route d'administration pour la liste des devis
    #[Route('/admin/devis', name: 'admin_devis_list')]
    public function list(EntityManagerInterface $em): Response
    {
        // Récupération de tous les devis
        $devis = $em->getRepository(Devis::class)->findAll();

        // Renvoi vers le template de la liste des devis
        return $this->render('devis/list.html.twig', [
            'title' => 'Liste des Devis',
            'devis' => $devis,
        ]);
    }

    // Création d'un devis à partir d'un ticket Hive
    #[Route('/admin/devis/create-from-hive', name: 'admin_devis_create_from_hive')]
    public function createFromHive(EntityManagerInterface $em): Response
    {
        try {
            // Récupération du token depuis .env
            $apiToken = $this->getParameter('HIVE_API_TOKEN');

            // Appel à l'API Hive pour récupérer les données du ticket
            $response = $this->client->request('GET', 'https://api.hive.com/v1/tickets/{ticket_id}', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken, // Utilisation du token
                ],
            ]);

            // Vérification du statut HTTP
            if ($response->getStatusCode() !== 200) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de l\'appel à l\'API Hive');
            }

            // Traitement de la réponse
            $hiveData = $response->toArray();

            // Validation des données Hive avant la création
            if (!isset($hiveData['client']) || !isset($hiveData['montant'])) {
                $this->addFlash('error', 'Les données du ticket Hive sont incomplètes.');
                return $this->redirectToRoute('admin_devis_list');
            }

            // Création d'un nouveau devis à partir des données Hive
            $devis = new Devis();
            $devis->setClient($hiveData['client']);  // Par exemple, récupérer le nom du client
            $devis->setMontant($hiveData['montant']); // Récupérer le montant
            // Ajouter d'autres champs selon ta structure de Devis et les données Hive

            $em->persist($devis);
            $em->flush();

            // Redirection après la création
            $this->addFlash('success', 'Devis créé avec succès.');
            return $this->redirectToRoute('admin_devis_list');

        } catch (\Exception $e) {
            // Gestion des erreurs d'API ou autres exceptions
            $this->addFlash('error', 'Une erreur est survenue lors de la création du devis : ' . $e->getMessage());
            return $this->redirectToRoute('admin_devis_list');
        }
    }

    // Modification d'un devis existant avec historisation
    #[Route('/admin/devis/{id}/edit', name: 'admin_devis_edit')]
    public function edit(Request $request, Devis $devis, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DevisType::class, $devis); // Création du formulaire de modification

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Historisation avant mise à jour
            $ancienMontant = $devis->getMontant();  // Capture de l'ancien montant avant modification

            $em->flush(); // Mise à jour du devis

            // Créer une nouvelle entrée dans l'historique
            $historique = new HistoriqueDocument();
            $historique->setTypeDocument('devis');
            $historique->setDocumentId($devis->getId());
            $historique->setDateModification(new \DateTime());
            $historique->setAncienMontant($ancienMontant);
            $historique->setNouveauMontant($devis->getMontant());
            $historique->setDescriptionModification('Devis modifié.');

            $em->persist($historique);  // Sauvegarde de l'historique
            $em->flush();

            try {
                // Notifier Hive de la modification du devis
                $this->updateHiveTicket($devis);

                $this->addFlash('success', 'Devis modifié avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la mise à jour du ticket Hive : ' . $e->getMessage());
            }

            return $this->redirectToRoute('admin_devis_list'); // Redirection après modification
        }

        // Affichage du formulaire de modification
        return $this->render('devis/edit.html.twig', [
            'form' => $form->createView(),
            'devis' => $devis,
        ]);
    }

    // Ajout de la méthode pour notifier Hive d'une mise à jour
    private function updateHiveTicket(Devis $devis)
    {
        try {
            // Appel à l'API Hive pour mettre à jour le ticket associé au devis
            $response = $this->client->request('PATCH', 'https://api.hive.com/v1/tickets/{hive_ticket_id}', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getParameter('HIVE_API_TOKEN'), // Utilisation du token Hive
                ],
                'json' => [
                    'description' => 'Le devis #' . $devis->getId() . ' a été mis à jour.',
                    'status' => 'in_progress',
                ],
            ]);

            // Vérification du statut HTTP
            if ($response->getStatusCode() !== 200) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la mise à jour du ticket Hive');
            }

        } catch (\Exception $e) {
            throw new \Exception('Impossible de notifier Hive : ' . $e->getMessage());
        }
    }

    // Suppression d'un devis
    #[Route('/admin/devis/{id}/delete', name: 'admin_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devis, EntityManagerInterface $em): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $devis->getId(), $request->request->get('_token'))) {
            $em->remove($devis); // Suppression du devis
            $em->flush();

            try {
                // Notifier Hive de la suppression du devis
                $this->closeHiveTicket($devis);

                $this->addFlash('success', 'Devis supprimé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la fermeture du ticket Hive : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('admin_devis_list'); // Redirection après suppression
    }

    // Ajout de la méthode pour fermer un ticket Hive lors de la suppression
    private function closeHiveTicket(Devis $devis)
    {
        try {
            // Appel à l'API Hive pour fermer le ticket associé au devis
            $response = $this->client->request('PATCH', 'https://api.hive.com/v1/tickets/{hive_ticket_id}', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getParameter('HIVE_API_TOKEN'), // Utilisation du token Hive
                ],
                'json' => [
                    'status' => 'closed',
                ],
            ]);

            // Vérification du statut HTTP
            if ($response->getStatusCode() !== 200) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la fermeture du ticket Hive');
            }

        } catch (\Exception $e) {
            throw new \Exception('Impossible de fermer le ticket Hive : ' . $e->getMessage());
        }
    }
}
