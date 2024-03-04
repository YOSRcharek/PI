<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
/**************************************************************************************************** */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
/****************************************************************************************************/
    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
/****************************************************************************************************/
    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
/****************************************************************************************************/
    public function search(string $query = '')
    {
        $qb = $this->createQueryBuilder('p');
        
        $qb->where('p.title LIKE :query')
           ->setParameter('query', '%'.$query.'%')
           ->orderBy('p.title', 'ASC');
        
        return $qb->getQuery()->getResult();
    }

    
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                FROM App\Entity\Post e
                WHERE e.title LIKE :str OR e.content LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }



    public function findAllWithComments(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->addSelect('c')
            ->orderBy('p.createdat', 'DESC');

        return $qb->getQuery()->getResult();
    }


    
//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}