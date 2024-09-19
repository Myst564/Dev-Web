<?php 

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\HistoriqueDocument; // Import de l'entité HistoriqueDocument
use App\Form\FactureType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\MailerInterface; // Pour l'envoi d'emails
use Symfony\Component\Mime\Email; // Pour la création d'emails

class FactureController extends AbstractController 
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    // Route pour le menu Facturation (facturation_index)
    #[Route('/facturation', name: 'facturation_index')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupération des factures à afficher
        $factures = $em->getRepository(Facture::class)->findAll();

        // Renvoi vers le template de la page de facturation
        return $this->render('facture/index.html.twig', [
            'title' => 'Facturation',
            'factures' => $factures,
        ]);
    }

    // Route d'administration de la liste des factures
    #[Route('/admin/factures', name: 'admin_facture_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $factures = $em->getRepository(Facture::class)->findAll();

        return $this->render('facture/list.html.twig', [
            'title' => 'Liste des Factures',
            'factures' => $factures,
        ]);
    }

    // Envoi d'une relance de paiement
    #[Route('/admin/facture/{id}/relance', name: 'admin_facture_relance')]
    public function sendRelance(Facture $facture, MailerInterface $mailer): Response
    {
        // Création de l'email de relance
        $email = (new Email())
            ->from('no-reply@yourdomain.com')  // L'adresse d'envoi
            ->to($facture->getClient()->getEmail())  // L'adresse email du client
            ->subject('Relance de paiement pour votre facture #' . $facture->getId())
            ->html($this->renderView('emails/relance_facture.html.twig', [
                'facture' => $facture
            ]));  // Utilisation d'un template Twig pour le corps de l'email

        // Envoi de l'email
        $mailer->send($email);

        // Confirmation de l'envoi
        $this->addFlash('success', 'Relance envoyée au client.');

        return $this->redirectToRoute('admin_facture_list'); // Redirection après l'envoi de la relance
    }

    // Création d'une facture à partir d'un ticket Hive
    #[Route('/admin/facture/create-from-hive', name: 'admin_facture_create_from_hive')]
    public function createFromHive(Request $request, EntityManagerInterface $em): Response
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
                return $this->redirectToRoute('admin_facture_list');
            }

            // Création d'une nouvelle facture à partir des données Hive
            $facture = new Facture();
            $facture->setClient($hiveData['client']);  // Récupérer le nom du client
            $facture->setMontant($hiveData['montant']); // Récupérer le montant
            $facture->setStatut('a_traiter'); // Statut par défaut

            $em->persist($facture);
            $em->flush();

            // Redirection après la création
            $this->addFlash('success', 'Facture créée avec succès.');
            return $this->redirectToRoute('admin_facture_list');
            
        } catch (\Exception $e) {
            // Gestion des erreurs d'API ou autres exceptions
            $this->addFlash('error', 'Une erreur est survenue lors de la création de la facture : ' . $e->getMessage());
            return $this->redirectToRoute('admin_facture_list');
        }
    }

    // Modification d'une facture existante avec historisation
    #[Route('/admin/facture/{id}/edit', name: 'admin_facture_edit')]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FactureType::class, $facture); // Création du formulaire de modification

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Historisation avant mise à jour
            $ancienMontant = $facture->getMontant();  // Capture de l'ancien montant avant modification

            $em->flush(); // Mise à jour de la facture

            // Créer une nouvelle entrée dans l'historique
            $historique = new HistoriqueDocument();
            $historique->setTypeDocument('facture');
            $historique->setDocumentId($facture->getId());
            $historique->setDateModification(new \DateTime());
            $historique->setAncienMontant($ancienMontant);
            $historique->setNouveauMontant($facture->getMontant());
            $historique->setDescriptionModification('Facture modifiée.');

            $em->persist($historique);  // Sauvegarde de l'historique
            $em->flush();

            $this->addFlash('success', 'Facture modifiée avec succès.');
            return $this->redirectToRoute('admin_facture_list'); // Redirection après modification
        }

        // Affichage du formulaire de modification
        return $this->render('facture/edit.html.twig', [
            'form' => $form->createView(),
            'facture' => $facture,
        ]);
    }

    // Suppression d'une facture
    #[Route('/admin/facture/{id}/delete', name: 'admin_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $facture->getId(), $request->request->get('_token'))) {
            $em->remove($facture); // Suppression de la facture
            $em->flush();

            $this->addFlash('success', 'Facture supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_facture_list'); // Redirection après suppression
    }

    // Route pour les factures à traiter
    #[Route('/admin/factures/a-traiter', name: 'admin_facture_a_traiter')]
    public function facturesATraiter(EntityManagerInterface $em): Response
    {
        $factures = $em->getRepository(Facture::class)->findBy(['statut' => 'a_traiter']);

        return $this->render('facture/list.html.twig', [
            'title' => 'Factures à traiter',
            'factures' => $factures,
        ]);
    }

    // Affichage de l'historique des modifications d'une facture
    #[Route('/admin/facture/{id}/historique', name: 'admin_facture_historique')]
    public function historique(EntityManagerInterface $em, $id): Response
    {
        // Récupérer l'historique des modifications pour cette facture
        $historique = $em->getRepository(HistoriqueDocument::class)->findBy([
            'documentId' => $id,
            'typeDocument' => 'facture',
        ]);

        return $this->render('facture/historique.html.twig', [
            'historique' => $historique,
            'factureId' => $id,
        ]);
    }
}
