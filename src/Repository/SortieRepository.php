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
        $today = new \DateTime;
        $todayMinusOneMonth = clone $today;
        $todayMinusOneMonth->modify('-1 month');
        
        $queryBuilder = $this->createQueryBuilder('sorties')
            ->leftJoin('sorties.campus', 'campus')
            ->leftJoin('sorties.etat', 'etat')
            ->leftJoin('sorties.participants', 'participant')
            ->addSelect('campus', 'etat', 'participant')
            ->andWhere('sorties.dateHeureDebut >= :todayMinusOneMonth')
            ->setParameter('todayMinusOneMonth', $todayMinusOneMonth)
        ;

        if (!$filters->show_closed_sorties) {
            $queryBuilder   ->andWhere('sorties.dateHeureDebut >= :today')
                            ->setParameter('today', $today)
            ;
        }

        if (!empty($filters->campus)) {
            $queryBuilder   ->andWhere('campus.id = :campusId')
                            ->setParameter('campusId', $filters->campus->getId());
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
            $queryBuilder   ->andWhere('sorties.organisateur = :userId')
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

        if (!$filters->show_closed_sorties) {
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
