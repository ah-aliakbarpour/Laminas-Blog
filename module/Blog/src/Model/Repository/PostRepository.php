<?php

namespace Blog\Model\Repository;

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

    /**
     * Create query builder for get posts from database
     */
    public function get(string $search, array $paginationData = [], bool $total = false, int $id = 0): array
    {
        // Select
        if ($total)
            $select = 'count(post.id) as total';
        elseif ($id)
            $select = 'partial post.{id, title, context, createdAt, updatedAt}';
        else
            $select = 'partial post.{id, title, context, createdAt}, partial user.{id, name}';

        // Create body
        $queryBuilder = $this->createQueryBuilder('post')
            ->select($select)
            ->join('post.user', 'user');

        // Search
        if ($search != '') {
            $queryBuilder
                ->where('user.name LIKE :searchName')
                ->setParameter('searchName', '%'.$search.'%')
                ->orWhere('post.title LIKE :searchTitle')
                ->setParameter('searchTitle', '%'.$search.'%')
                ->orWhere('post.context LIKE :searchContext')
                ->setParameter('searchContext', '%'.$search.'%');
        }

        // Pagination
        if (empty(!$paginationData)) {
            $queryBuilder
                ->setFirstResult($paginationData['start'])
                ->setMaxResults($paginationData['maxResults']);
        }

        // Find by id
        if ($id) {
            $queryBuilder
                ->where('post.id = :id')
                ->setParameter('id', $id);
        }

        $queryBuilder = $queryBuilder
            ->orderBy('post.id')
            ->getQuery();

        if ($total)
            $result = $queryBuilder->getSingleResult();
        else
            $result = $queryBuilder->getResult();

        return $result;
    }
}