<?php

namespace User\Model\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use User\Model\Repository\UserRepository;

interface UserModelServiceInterface
{
    public function __construct(EntityManager $entityManager);

    // Get user repository
    public function userRepository(): UserRepository;
}