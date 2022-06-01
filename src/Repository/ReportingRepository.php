<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Reporting;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Reporting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reporting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reporting[]    findAll()
 * @method Reporting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reporting::class);
    }

    /**
    * Récupère les signalements en lien avec une recherche
    * @return Reporting[]
    */
    // Passe un objet de type searchData
    public function findSearch(SearchDaTa $search)
    {
       
        $query = $this
            ->createQueryBuilder('r')
            ->Join('r.manuscript', 'm')
            //->where('r.manuscript = m.id')
            ->innerjoin('m.author_id', 'u')
            //->andWhere('m.author_id = r.id')
            ->andWhere('u.email = :val')
            ->orWhere('u.name = :val')
            ->setParameter('val', $search->authors)
            ;
            //dd($this->createQueryBuilder('r')->andWhere('user LIKE :q'));
            // Me renvoie toutes les entités...
            // if(!empty($search->authors)) {
            //     $query = $query
            //         ->AndWhere('r.user  LIKE :authors') 
            //         ->setParameter('authors', '%{$search->authors}%');
            // }
            //dd($query->getQuery()->getDql());
            //$query->getQuery()->getResult());
            return $query->getQuery()->getResult();

        

    }
}
