<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByMostLiked()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.likes', 'l')
            ->groupBy('a.id')
            ->orderBy('COUNT(l)', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Finds the articles liked by the logged in user from most newest to oldest
    public function findByUserLikes(User $user)
    {
        return $this->createQueryBuilder('a')
            ->join('a.likes', 'l')
            ->where('l.id = :userId')
            ->setParameter('userId', $user->getId(), UuidType::NAME)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByMostRecent()
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
