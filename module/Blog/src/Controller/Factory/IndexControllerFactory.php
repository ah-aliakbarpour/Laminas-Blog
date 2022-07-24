<?php

namespace Blog\Controller\Factory;

use Blog\Controller\IndexController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IndexController
    {
        $postService = $container->get(PostService::class);

        return new IndexController($postService);
    }
}