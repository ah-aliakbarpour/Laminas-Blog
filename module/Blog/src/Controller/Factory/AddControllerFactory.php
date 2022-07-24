<?php

namespace Blog\Controller\Factory;

use Blog\Controller\AddController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthService;

class AddControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AddController
    {
        $postService = $container->get(PostService::class);
        $authService = $container->get(AuthService::class);

        return new AddController($postService, $authService);
    }
}