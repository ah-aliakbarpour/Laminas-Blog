<?php

namespace Blog\Model\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\PostRepository;
use Doctrine\ORM\EntityManager;

class BlogModelService implements BlogModelServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postRepository(): PostRepository
    {
        return $this->entityManager->getRepository(PostEntity::class);
    }
}