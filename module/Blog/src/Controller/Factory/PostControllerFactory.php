<?php

namespace Blog\Controller\Factory;

use Blog\Controller\PostController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthService;

class PostControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PostController
    {
        $postService = $container->get(PostService::class);
        $authService = $container->get(AuthService::class);

        return new PostController($postService, $authService);
    }
}