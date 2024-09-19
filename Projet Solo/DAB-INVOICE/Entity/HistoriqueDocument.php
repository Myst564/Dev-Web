<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


Class HistoriqueDocument
{
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', lenght: 255)]
    private $typeDocument;

    #[ORM\Column(type:'integer')]
    private $documentId;

    #[ORM\Column(type: 'datetime')]
    private $dateModification;

    #[ORM\Column(type:'float')]
    private $ancienMontant;

    #[ORM\Column(type:'text')]
    private $descriptionModification;
}
