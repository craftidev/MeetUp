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

    public function findSortiesWithFilters($filters, $userId)
    {
        // $filters->campus
        // $filters->name_search
        // $filters->range_start
        // $filters->range_end
        // $filters->i_am_organisateur
        // $filters->i_am_subscribed
        // $filters->i_am_not_subscribed
        // $filters->show_closed_sorties

        $queryBuilder = $this->createQueryBuilder('sorties');

        if (!empty($filters->campus)) {
            $queryBuilder   ->andWhere(':campus = sorties.campus')
                            ->setParameter('campus', $filters->campus);
        }

        if (!empty($filters->name_search)) {
            $queryBuilder   ->andWhere($queryBuilder
                                ->expr()
                                ->like('sorties.nom', ':nom'))
                            ->setParameter('nom', '%' . $filters->name_search . '%');
        }

        if (!empty($filters->range_start)) {
            $queryBuilder   ->andWhere('sorties.dateHeureDebut >= :range_start')
                            ->setParameter('range_start', $filters->range_start)
            ;
        }

        if (!empty($filters->range_end)) {
            $queryBuilder   ->andWhere('sorties.dateHeureDebut <= :range_end')
                            ->setParameter('range_end', $filters->range_end)
            ;
        }

        if ($filters->i_am_organisateur) {
            $queryBuilder   ->andWhere(':userId = sorties.organisateur')
                            ->setParameter('userId', $userId)
            ;
        }

        if ($filters->i_am_subscribed) {
            $queryBuilder   ->andWhere(':userId MEMBER OF sorties.participants')
                            ->setParameter('userId', $userId)
            ;
        }

        if ($filters->i_am_not_subscribed) {
            $queryBuilder   ->andWhere(':userId NOT MEMBER OF sorties.participants')
                            ->setParameter('userId', $userId)
            ;
        }

        if ($filters->show_closed_sorties) {
            $today = new \DateTime;
            $queryBuilder   ->andWhere('sorties.dateHeureDebut >= :today')
                            ->setParameter('today', $today)
            ;
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
