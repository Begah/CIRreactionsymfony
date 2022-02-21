<?php

/**
 * Espece repository, used to interact with the espece table in the database
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

namespace App\Repository;

use App\Entity\Espece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Espece|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espece|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espece[]    findAll()
 * @method Espece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspeceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espece::class);
    }

    // /**
    //  * @return Espece[] Returns an array of Espece objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Espece
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // Find all especes beginning with value
    public function findEntitiesByName(string $value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere("e.espece LIKE :val")
            ->setParameter('val', $value . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    // Find the only espece via the name
    public function findEntityByName(string $value): ?Espece
    {
        return $this->createQueryBuilder('e')
            ->andWhere("e.espece LIKE :val")
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
