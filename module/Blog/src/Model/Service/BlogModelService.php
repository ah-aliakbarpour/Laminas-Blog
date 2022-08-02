<?php

namespace Blog\Model\Service;

use Blog\Model\Entity\CommentEntity;
use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\CommentRepository;
use Blog\Model\Repository\PostRepository;
use Doctrine\ORM\Decorator\EntityManagerDecorator;

class BlogModelService extends EntityManagerDecorator implements BlogModelServiceInterface
{
    /**
     * @return PostRepository
     */
    public function getPostRepository(): PostRepository
    {
        return $this->getRepository(PostEntity::class);
    }

    /**
     * @return CommentRepository
     */
    public function getCommentRepository(): CommentRepository
    {
        return $this->getRepository(CommentEntity::class);
    }
}