<?php

namespace Blog\Controller\Factory;

use Blog\Controller\DeleteController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthService;

class DeleteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DeleteController
    {
        $postService = $container->get(PostService::class);
        $authService = $container->get(AuthService::class);

        return new DeleteController($postService, $authService);
    }
}