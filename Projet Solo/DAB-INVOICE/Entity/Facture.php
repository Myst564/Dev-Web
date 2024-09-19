<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FactureRepository;
use App\Entity\Client;
use App\Entity\Vente;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $documentName;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $increased = false;

    #[ORM\Column(type: 'boolean')]
    private bool $paid = false;

    // Statut pour savoir si la facture a été envoyée par email
    #[ORM\Column(type: 'boolean')]
    private bool $sent = false;

    // Statut pour suivre si la facture a été relancée
    #[ORM\Column(type: 'boolean')]
    private bool $reminderSent = false;

    #[ORM\ManyToOne(targetEntity: Vente::class, inversedBy: 'factures')]
    private Vente $vente;

    // Relation avec l'entité Client
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    public function setDocumentName(string $documentName): self
    {
        $this->documentName = $documentName;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isIncreased(): bool
    {
        return $this->increased;
    }

    public function setIncreased(bool $increased): self
    {
        $this->increased = $increased;
        return $this;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;
        return $this;
    }

    public function isSent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;
        return $this;
    }

    public function isReminderSent(): bool
    {
        return $this->reminderSent;
    }

    public function setReminderSent(bool $reminderSent): self
    {
        $this->reminderSent = $reminderSent;
        return $this;
    }

    public function getVente(): Vente
    {
        return $this->vente;
    }

    public function setVente(Vente $vente): self
    {
        $this->vente = $vente;
        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}
