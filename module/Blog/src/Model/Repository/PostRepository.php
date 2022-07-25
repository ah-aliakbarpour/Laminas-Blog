<?php

namespace Blog\Model\Repository;

use Blog\Model\Entity\PostEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class PostRepository extends EntityRepository
{
    /**
     * Doctrine entity manager.
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->entityManager = $this->getEntityManager();
    }

    // Create query builder for get posts from database
    public function get(string $select, array $queries = [], bool $singleResult = false)
    {
        $queryBuilder = $this->createQueryBuilder('post')
            ->select($select)
            ->join('post.user', 'user');

        foreach ($queries as $query)
            switch ($query[0]) {
                case 'where':
                    $queryBuilder->where($query[1])
                        ->setParameter('value', $query[2]);
                    break;

                case 'andWhere':
                    $queryBuilder->andWhere($query[1])
                        ->setParameter('value', $query[2]);
                    break;

                case 'orWhere':
                    $queryBuilder->orWhere($query[1])
                        ->setParameter('value', $query[2]);
                    break;

                case 'orderBy':
                    $queryBuilder->orderBy($query[1]);
                    break;

                case 'setFirstResult':
                    $queryBuilder->setFirstResult($query[1]);
                    break;

                case 'setMaxResults':
                    $queryBuilder->setMaxResults($query[1]);
                    break;

                default:
                    break;
            }

        $queryBuilder = $queryBuilder->getQuery();

        if ($singleResult)
            $result = $queryBuilder->getSingleResult();
        else
            $result = $queryBuilder->getResult();

        return $result;
    }
}