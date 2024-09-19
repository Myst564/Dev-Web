<?php 

namespace App\Command;

use App\Entity\Facture;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckUnpaidInvoicesCommand extends Command
{
    protected static $defaultName = 'app:check-unpaid-invoices';
    private $em;
    private $emailService;

    public function __construct(EntityManagerInterface $em, EmailService $emailService)
    {
        parent::__construct();
        $this->em = $em;
        $this->emailService = $emailService;
    }

    public function sendTeamNotification($facture)
    {
        $emailContent = $this->twig->render('emails/unpaid_invoice_notification.html.twig', [
             'facture' => $facture,
        ]);

        $email = (new Email())
             ->from('noreply@tonapp.com')
             ->to('team@tonapp.com') // Adresse de l'équipe
             ->subject('Notification: Facture non acquittée #'. $facture->getId())
             ->html($emailContent);

        $this->mailer->send($email);
    }



    protected function configure()
    {
        $this->setDescription('Vérifie les factures non acquittées et envoie des notifications.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // On récupère les factures non acquittées
        $today = new \DateTime();
        $factures = $this->em->getRepository(Facture::class)->findBy(['isPaid' => false]);

        foreach ($factures as $facture) {
            // Vérifier si la date limite de paiement est dépassée
            if ($facture->getDueDate() < $today) {
                // Notifier l'équipe en interne
                $this->emailService->sendTeamNotification($facture);
            }
        }

        $output->writeln('Notifications envoyées pour les factures impayées.');

        return Command::SUCCESS;
    }
}
