<?php

namespace User\Model\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use User\Model\Entity\UserEntity;

class UserRepository extends EntityRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->entityManager = $this->getEntityManager();
    }

    public function save(UserEntity $user)
    {
        $user->setCreatedAt(date('Y-m-d H:i:s'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}