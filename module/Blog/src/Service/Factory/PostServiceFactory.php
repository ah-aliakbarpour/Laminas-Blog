<?php

namespace Blog\Service\Factory;

use Blog\Model\Service\BlogModelService;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthService;

class PostServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PostService
    {
        $blogModelService = $container->get(BlogModelService::class);
        $authService = $container->get(AuthService::class);

        return new PostService($blogModelService, $authService);
    }
}