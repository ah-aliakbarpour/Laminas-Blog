<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Service\BlogModelService;

interface CommentServiceInterface
{
    public function __construct(BlogModelService $blogModelService);

    // Add new comment
    public function add(PostEntity $post, array $data): array;
}