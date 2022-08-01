<?php

namespace Blog\Model\Service;

use Blog\Model\Entity\CommentEntity;
use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\CommentRepository;
use Blog\Model\Repository\PostRepository;
use Doctrine\ORM\Decorator\EntityManagerDecorator;

class BlogModelService extends EntityManagerDecorator implements BlogModelServiceInterface
{
    public function postRepository(): PostRepository
    {
        return $this->getRepository(PostEntity::class);
    }

    public function commentRepository(): CommentRepository
    {
        return $this->getRepository(CommentEntity::class);
    }
}