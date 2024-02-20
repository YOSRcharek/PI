<?php

namespace App\Repository;

use App\Entity\TypeDons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDons>
 *
 * @method TypeDons|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDons|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDons[]    findAll()
 * @method TypeDons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDons::class);
    }

//    /**
//     * @return TypeDons[] Returns an array of TypeDons objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeDons
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
