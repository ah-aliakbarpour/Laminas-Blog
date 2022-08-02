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
    /**
     * @param BlogModelService $blogModelService
     * @param AuthService $authService
     */
    public function __construct(BlogModelService $blogModelService, AuthService $authService);

    /**
     * Find a single post by id
     * @param string $postId
     * @return array
     */
    public function find(string $postId): array;

    /**
     *  Find all postt
     * @param string $search
     * @param $currentPage
     * @param int $itemsPerPage
     * @return array
     */
    public function findAll(string $search, $currentPage, int $itemsPerPage): array;

    /**
     * Add/Edit new post
     * @param array $data
     * @return array
     */
    public function save(array $data): array;

    /**
     * Delete post
     * @param string $postId
     * @return array
     */
    public function delete(string $postId): array;

    /**
     * Check user access
     * @param bool $identity
     * @param int $postId
     * @param bool $exists
     * @param bool $access
     * @return array
     */
    public function access(bool $identity, int $postId = -1, bool $exists = false, bool $access = false): array;
}