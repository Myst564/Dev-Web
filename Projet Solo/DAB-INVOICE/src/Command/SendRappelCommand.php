<?php 

namespace App\Command;

use App\Repository\FactureRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendRappelCommand extends Command
{
    protected static $defaultName = 'app:send-rappel';
    private $factureRepository;
    private $mailer;

    public function __construct(FactureRepository $factureRepository, MailerInterface $mailer)
    {
        $this->factureRepository = $factureRepository;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérer les factures à traiter
        $factures = $this->factureRepository->findBy(['statut' => 'a_traiter']);

        foreach ($factures as $facture) {
            // Envoi de l'email de rappel
            $email = (new Email())
                ->from('no-reply@yourdomain.com')
                ->to($facture->getClient()->getEmail())
                ->subject('Rappel : Facture #' . $facture->getId() . ' à traiter')
                ->text('Veuillez traiter la facture #' . $facture->getId());

            $this->mailer->send($email);
        }

        $output->writeln('Rappels envoyés avec succès.');

        return Command::SUCCESS;
    }
}
