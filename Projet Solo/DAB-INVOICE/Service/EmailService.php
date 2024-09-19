<?php 

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendPaymentReminder($clientEmail, $facture)
    {
        // Génération de l'email via Twig (tu peux créer un template Twig pour le contenu du mail)
        $emailContent = $this->twig->render('emails/payment_reminder.html.twig', [
            'facture' => $facture,
            'client' => $facture->getClient(),
        ]);

        // Construction du mail
        $email = (new Email())
            ->from('noreply@tonapp.com')
            ->to($clientEmail)
            ->subject('Relance de paiement pour la facture #' . $facture->getId())
            ->html($emailContent);

        // Envoi de l'email
        $this->mailer->send($email);
    }
}
