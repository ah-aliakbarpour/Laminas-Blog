<?php

namespace Blog\Model\Repository;

use Blog\Model\Entity\PostEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use http\QueryString;
use PhpParser\Lexer\TokenEmulator\ReadonlyTokenEmulator;

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

    public function search(string $search): QueryBuilder
    {
        $query = $this->createQueryBuilder('post')
            ->select('post, user')
            ->join('post.user', 'user')
            ->where('user.name LIKE :author')
            ->orWhere('post.title LIKE :title')
            ->orWhere('post.context LIKE :context')
            ->setParameters([
                'author' => '%'.$search.'%',
                'title' => '%'.$search.'%',
                'context' => '%'.$search.'%',
            ]);

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

    public function countQuery(QueryBuilder $query)
    {


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