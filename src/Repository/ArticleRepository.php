<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    /**
     * Find articles with specific content
     * @param $value string Searched value
     * @return mixed
     */
    public function findTitleOrContentWith(string $value): mixed
    {
        return $this->createQueryBuilder('a')
            ->andWhere("a.content LIKE :value")
            ->orWhere("a.title LIKE :value")
            ->setParameter(':value', "%$value%")
            ->getQuery()
            ->getResult();
    }

    /**
     * Get a group of all articles years
     * @return mixed
     */
    public function getYearsOfArticles(): mixed
    {
        return $this->createQueryBuilder('a')
            ->select('YEAR(a.creationDate) AS year')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param int|string $year Searched year
     * @return mixed
     */
    public function getArticlesByYear(int|string $year): mixed
    {
        return $this->createQueryBuilder('a')
            ->where('YEAR(a.creationDate) = :year')
            ->setParameter(':year', $year)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
