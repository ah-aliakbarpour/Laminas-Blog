<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Service\BlogModelService;

/**
 * For: Blog\Service\PostService
 */
interface CommentServiceInterface
{
    public function __construct(BlogModelService $blogModelService, PostService $postService);

    // Add comment
    public function save(array $data): array;
}