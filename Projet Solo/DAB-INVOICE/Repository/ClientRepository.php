<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

     /**
     * @return Client[] Returns an array of non-archived Client objects
     */
    public function findAllNonArchived(): array
    {
        return $this->createQuerybuilder('c')
        ->andWhere('c.archivedAt IS NULL')
        -> getQuery()
        -> getResult();
    }

    /**
     * @return Client[] Returns an array of archived Client objects
     */
    public function findAllArchived(): array
    {
        return $this->createQueryBuilder('c')
        -> andWhere('c.archivedAt IS NOT NULL')
        ->getQuery()
        ->getResult();
    }
}
