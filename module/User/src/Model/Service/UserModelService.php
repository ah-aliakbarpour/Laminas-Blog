<?php

namespace User\Model\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use User\Model\Entity\UserEntity;
use User\Model\Repository\UserRepository;

class UserModelService implements UserModelServiceInterface
{
    /**
     * Doctrine entity manager.
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function userRepository(): UserRepository
    {
        return $this->entityManager->getRepository(UserEntity::class);
    }
}