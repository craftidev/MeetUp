<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findSortiesWithFilters($filters)
    {
        // $filters['campus']
        // $filters['name_search']
        // $filters['range_start']
        // $filters['range_end']
        // $filters['i_am_organisateur']
        // $filters['i_am_subscribed']
        // $filters['i_am_not_subscribed']
        // $filters['show_closed_sorties']

        $queryBuilder = $this->createQueryBuilder('sorties');

        if ($filters['campus'] != "all") {
            $queryBuilder->andWhere('sorties.campus = :campus')
            ->setParameter('campus', $filters['campus']);
        }

        if (!empty($filters['name_search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('sorties.name_search', ':name_search'))
            ->setParameter('name_search', '%' . $filters['name_search'] . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
