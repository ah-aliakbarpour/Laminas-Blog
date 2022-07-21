<?php

namespace Blog\Model\Repository;

use Blog\Model\Entity\PostEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class PostRepository extends EntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->entityManager = $this->getEntityManager();
    }


    public function save(PostEntity $post): void
    {
        if (!$post->getId()) {
            // Create
            $post->setCreatedAt(date('Y-m-d H:i:s'));

            $this->entityManager->persist($post);
        }
        else {
            // Update
            $post->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        $this->entityManager->flush();
    }

}