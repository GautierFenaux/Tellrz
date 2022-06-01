<?php

namespace App\Repository;

use App\Entity\Manuscript;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Manuscript|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manuscript|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manuscript[]    findAll()
 * @method Manuscript[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManuscriptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manuscript::class);
    }
    // création de findByGenre qui permet d'aller chercher les manuscrits par leur genre
    public function findByGenre($id) 
    {
        return $this->createQueryBuilder('m')

        ->join('m.genres', 'genre')
        ->Where('genre.id = :val')
        ->setParameter('val', $id)
        // ->orderBy('m.id', 'ASC')
        // ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
    }


    // /**
    //  * @return Manuscript[] Returns an array of Manuscript objects
    //  */
    /*
    public function findByLastId($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Manuscript
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
