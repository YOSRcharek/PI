<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

//    /**
//     * @return Service[] Returns an array of Service objects
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

//    public function findOneBySomeField($value): ?Service
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findSearch(SearchData $data): array
{
    $query = $this
        ->createQueryBuilder('s')
        ->select('c', 's')
        ->join('s.category', 'c');

    if (!empty($data->q)) {
        $query = $query
            ->andWhere('s.name LIKE :q')
            ->setParameter('q', "%{$data->q}%");
    }

    // Ajoutez d'autres conditions en fonction de vos besoins

    return $query->getQuery()->getResult();
}
public function service(ServiceRepository $repository, Knp\Component\Pager\PaginatorInterface $paginator, Request $request)
{
    $query = $repository->createQueryBuilder('s')->getQuery();
    $services = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
    return $this->render('front/Service/paginator.html.twig', ['Services' => $services]);
}


}
