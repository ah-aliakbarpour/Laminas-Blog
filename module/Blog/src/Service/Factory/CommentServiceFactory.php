<?php

namespace Blog\Service\Factory;

use Blog\Model\Service\BlogModelService;
use Blog\Service\CommentService;
use Blog\Service\PostService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class CommentServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CommentService
    {
        $blogModelService = $container->get(BlogModelService::class);
        $postService = $container->get(PostService::class);

        return new CommentService($blogModelService, $postService);
    }
}