<?php

namespace Blog\Plugin;

use Blog\Service\PostService;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class AccessPlugin extends AbstractPlugin
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param $postId
     * @return array
     */
    public function view($postId): array
    {
        return $this->postService->access(false, $postId, true);
    }

    /**
     * @return array
     */
    public function add(): array
    {
        return $this->postService->access(true);
    }

    /**
     * @param $postId
     * @return array
     */
    public function edit($postId): array
    {
        return $this->postService->access(true, $postId, true, true);
    }

    /**
     * @param $postId
     * @return array
     */
    public function delete($postId): array
    {
        return $this->postService->access(true, $postId, true, true);
    }
}