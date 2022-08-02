<?php

namespace Blog\Model\Service;

use Blog\Model\Repository\CommentRepository;
use Blog\Model\Repository\PostRepository;

interface BlogModelServiceInterface
{
    /**
     * Get post repository
     * @return PostRepository
     */
    public function getPostRepository(): PostRepository;

    /**
     * Get comment repository
     * @return CommentRepository
     */
    public function getCommentRepository(): CommentRepository;

}