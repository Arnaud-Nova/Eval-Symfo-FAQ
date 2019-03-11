<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findQuestionsByTagModo(Tag $tag)
    {
        $query = $this->getEntityManager()->createQuery('
        SELECT question
        FROM App\Entity\Question question
        JOIN question.tags t
        WHERE t = :tag
        ')
        ->setParameter('tag',$tag);

        return $query->getResult(); 
    }

    public function findQuestionsByTag(Tag $tag)
    {
        $query = $this->getEntityManager()->createQuery('
        SELECT question
        FROM App\Entity\Question question
        JOIN question.tags t
        WHERE t = :tag
        AND question.isActive = true
        ')
        ->setParameter('tag',$tag);

        return $query->getResult(); 
    }
    
    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
