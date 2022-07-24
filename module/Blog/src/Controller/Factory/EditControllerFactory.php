<?php

namespace Blog\Controller\Factory;

use Blog\Controller\EditController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthService;

class EditControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EditController
    {
        $postService = $container->get(PostService::class);
        $authService = $container->get(AuthService::class);

        return new EditController($postService, $authService);
    }
}