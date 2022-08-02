<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Service\BlogModelService;

/**
 * For: Blog\Service\PostService
 */
interface CommentServiceInterface
{
    /**
     * @param BlogModelService $blogModelService
     * @param PostService $postService
     */
    public function __construct(BlogModelService $blogModelService, PostService $postService);

    /**
     * Add comment
     * @param array $data
     * @return array
     */
    public function save(array $data): array;
}