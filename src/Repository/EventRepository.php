<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function findByNomEvent(string $searchTerm): array
    {
        try {
            $query = $this->createQueryBuilder('e')
                ->andWhere('e.nomEvent LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery();
    
            return $query->getResult();
        } catch (\Exception $e) {
            // Log l'erreur si nécessaire
            $this->logger->error('Une erreur s\'est produite lors de la recherche d\'événements : ' . $e->getMessage());
    
            // Renvoyer une tableau vide en cas d'erreur
            return [];
        }
    }
    public function findEventsByLocation(string $location): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.localisation = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }
   
    public function findTopEventsByCapacity(int $limit): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.capaciteMax', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findEventsByStartDate(\DateTimeInterface $startDate): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('DATE(e.dateDebut) = :startDate')  // Utiliser la fonction DATE pour comparer uniquement la date
            ->setParameter('startDate', $startDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }
    
    public function findEventsByEventType(string $typeEvent): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.type', 't')
            ->andWhere('t.nom = :typeEvent')
            ->setParameter('typeEvent', $typeEvent)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
