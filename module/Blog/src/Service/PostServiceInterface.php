<?php

namespace Blog\Service;

use Blog\Model\Entity\PostEntity;
use Blog\Model\Service\BlogModelService;
use Doctrine\ORM\QueryBuilder;
use User\Service\AuthService;

interface PostServiceInterface
{
    public function __construct(BlogModelService $blogModelService, AuthService $authService);

    // Get all posts
    public function getAll(): array;

    // Find a single post by id
    public function find($postId);

    // Add new post
    public function add(array $data): array;

    // Edit post
    public function edit(PostEntity $post, array $data): array;

    // Delete post
    public function delete(PostEntity $post): array;

    // Check if the post belongs to the current user
    public function userHasAccess(PostEntity $post): bool;

    // Paginate data
    public function paginate(string $search, int $currentPage, int $itemsPerPage): array;
}