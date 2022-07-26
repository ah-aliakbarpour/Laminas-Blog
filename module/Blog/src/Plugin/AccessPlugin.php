<?php

namespace Blog\Plugin;

use Blog\Controller\PostController;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Mvc\Controller\Plugin\Params;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;

class AccessPlugin extends AbstractPlugin
{
    /**
     * @var PostService
     */
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function view($postId)
    {
        return $this->postService->access(false, $postId, true);
    }

    public function add()
    {
        return $this->postService->access(true);
    }

    public function edit($postId)
    {
        return $this->postService->access(true, $postId, true, true);
    }

    public function delete($postId)
    {
        return $this->postService->access(true, $postId, true, true);
    }
}