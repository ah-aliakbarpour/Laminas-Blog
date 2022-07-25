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
    public $entityManager;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->entityManager = $this->getEntityManager();
    }
}