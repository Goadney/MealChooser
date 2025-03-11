<?php

namespace App\Repository;

use App\Entity\Repas;
use App\Entity\Saisons;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Repas>
 */
class RepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repas::class);
    }
    public function getBySlug($slug){return $this->findOneBy(['slug' => $slug]);}

    public function findWithFilter(String $duree = null,int $weekend = null,Saisons $saison = null)
    {
        $query = $this->createQueryBuilder('r');
        if($saison != null){
            $query
            ->innerJoin('r.saisons', 's')
            ->where('s.id = :saison_id')
            ->setParameter('saison_id', $saison->getId());
        };
        
        if($weekend != null){
            dump($weekend);
            $weekend == "1" ? $weekend = true : $weekend = false;
            $query
            ->andWhere('r.weekend = :weekend')
            ->setParameter('weekend', $weekend);
        };
        if($duree != null){
            $query
            ->andWhere('r.duree = :duree')
            ->setParameter('duree', $duree);
        };

        return $query
            ->getQuery()
            ->getResult();
    }
        //    /**
    //     * @return Repas[] Returns an array of Repas objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Repas
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
