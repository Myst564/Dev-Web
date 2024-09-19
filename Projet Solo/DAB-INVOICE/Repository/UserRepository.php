<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry
    )
    {
        parent::__construct($registry, User::class);
    }

    public function findAllActiveUser()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.archivedAt is null')
            ->getQuery()
            ->getResult();
    }

    public function findAllNotActiveUser()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.archivedAt is not null')
            ->addOrderBy('u.lastname', 'ASC')
            ->addOrderBy('u.firstname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllActiveUsersExceptMe(int $loggedInUserId)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.archivedAt is null')
            ->andWhere('u.id != :loggedUserId')
            ->setParameter('loggedUserId', $loggedInUserId)
            ->OrderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
