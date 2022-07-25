<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Service\BlogModelService;
use Doctrine\ORM\QueryBuilder;
use User\Service\AuthService;

/**
 * For: Blog\Service\PostService
 */
interface PostServiceInterface
{
    public function __construct(BlogModelService $blogModelService, AuthService $authService);

    // Find a single post by id
    public function find(string $postId): array;

    // Add/Edit new post
    public function save(array $data): array;

    // Delete post
    public function delete(string $postId): array;

    // Check user access
    public function access(bool $identity, $postId = -1, bool $exists = false, bool $access = false): array;

    // Paginate data
    public function paginate(string $search, $currentPage, int $itemsPerPage): array;
}