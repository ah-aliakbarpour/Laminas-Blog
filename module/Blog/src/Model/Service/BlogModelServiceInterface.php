<?php

namespace Blog\Model\Service;

use Blog\Model\Repository\CommentRepository;
use Blog\Model\Repository\PostRepository;
use Doctrine\ORM\EntityManager;

interface BlogModelServiceInterface
{
    public function __construct(EntityManager $entityManager);

    // Get post repository
    public function postRepository(): PostRepository;

    // Get comment repository
    public function commentRepository(): CommentRepository;
}