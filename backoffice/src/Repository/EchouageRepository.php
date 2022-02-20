<?php

namespace App\Repository;

use App\Entity\Echouage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Echouage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Echouage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Echouage[]    findAll()
 * @method Echouage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchouageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echouage::class);
    }

    // /**
    //  * @return Echouage[] Returns an array of Echouage objects
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
    public function findOneBySomeField($value): ?Echouage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findPage(int $page, int $num_per_page)
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'ASC')
            ->setFirstResult($page * $num_per_page)
            ->setMaxResults($num_per_page)
            ->getQuery()
            ->getResult();
    }

    public function pagesCount(int $num_per_page)
    {
        return intval(ceil($this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult() / $num_per_page));
    }

    public function findAny(int $zone_id, int $espece_id)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.zone = :zoneid')
            ->andWhere('e.espece = :especeid')
            ->setParameter('zoneid', $zone_id)
            ->setParameter('especeid', $espece_id)
            ->getQuery()
            ->getResult();
    }

    public function findDuring($zone_id, $espece_id, $annee_min, $annee_max)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.zone = :zoneid')
            ->andWhere('e.espece = :especeid')
            ->andWhere('e.date >= :annee_min')
            ->andWhere('e.date <= :annee_max')
            ->setParameter('zoneid', $zone_id)
            ->setParameter('especeid', $espece_id)
            ->setParameter('annee_min', $annee_min)
            ->setParameter('annee_max', $annee_max)
            ->getQuery()
            ->getResult();
    }
}
