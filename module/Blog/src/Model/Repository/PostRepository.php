<?php

namespace Blog\Model\Repository;

use Blog\Model\Entity\PostEntity;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class PostRepository extends EntityRepository
{
    /**
     * Doctrine entity manager.
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->entityManager = $this->getEntityManager();
    }

    public function get(string $select = 'post', array $queries = [], bool $singleResult = false)
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

    public function search(string $search): QueryBuilder
    {
        $query = $this->createQueryBuilder('post')
            ->select('partial post.{id, title, createdAt}, partial user.{id, name}')
            ->join('post.user', 'user')
            ->where('user.name LIKE :author')
            ->orWhere('post.title LIKE :title')
            ->orWhere('post.context LIKE :context')
            ->setParameters([
                'author' => '%'.$search.'%',
                'title' => '%'.$search.'%',
                'context' => '%'.$search.'%',
            ])
            ->orderBy('post.id');

        return $query;
    }

    public function startLimit(QueryBuilder $query, int $start, int $limit): array
    {
        $result = $query
            ->setFirstResult($start - 1)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function countAll(string $search): string
    {
        return $this->createQueryBuilder('post')
            ->select('count(post.id)')
            ->join('post.user', 'user')
            ->where('user.name LIKE :author')
            ->orWhere('post.title LIKE :title')
            ->orWhere('post.context LIKE :context')
            ->setParameters([
                'author' => '%'.$search.'%',
                'title' => '%'.$search.'%',
                'context' => '%'.$search.'%',
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(PostEntity $post): void
    {
        // Add
        if (!$post->getId()) {
            $post->setCreatedAt(date('Y-m-d H:i:s'));

            $this->entityManager->persist($post);
        }
        // Edit
        else {
            $post->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        $this->entityManager->flush();
    }

    public function delete(PostEntity $post): void
    {
        // Remove associated comments
        foreach ($post->getComments() as $comment)
            $this->entityManager->remove($comment);

        $this->entityManager->remove($post);

        $this->entityManager->flush();
    }
}