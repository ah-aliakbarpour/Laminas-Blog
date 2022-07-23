<?php

namespace Blog\Controller\Factory;

use Blog\Controller\CommentController;
use Blog\Service\CommentService;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CommentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CommentController
    {
        $commentService = $container->get(CommentService::class);
        $postService = $container->get(PostService::class);

        return new CommentController($commentService, $postService);
    }
}