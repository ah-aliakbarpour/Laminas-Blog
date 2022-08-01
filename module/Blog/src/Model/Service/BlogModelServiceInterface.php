<?php

namespace Blog\Model\Service;

use Blog\Model\Repository\CommentRepository;
use Blog\Model\Repository\PostRepository;

interface BlogModelServiceInterface
{
    // Get post repository
    public function postRepository(): PostRepository;

    // Get comment repository
    public function commentRepository(): CommentRepository;
}