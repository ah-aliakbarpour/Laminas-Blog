<?php

namespace Blog\Model\Repository;

use Blog\Model\Entity\CommentEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class CommentRepository extends EntityRepository
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

    public function save(CommentEntity $comment): void
    {
        // Add
        if (!$comment->getId()) {
            $comment->setCreatedAt(date('Y-m-d H:i:s'));

            $this->entityManager->persist($comment);
        }
        // Edit
        else {
            $comment->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        $this->entityManager->flush();
    }
}