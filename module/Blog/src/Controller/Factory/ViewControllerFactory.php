<?php

namespace Blog\Controller\Factory;

use Blog\Controller\ViewController;
use Blog\Service\PostService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ViewControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ViewController
    {
        $postService = $container->get(PostService::class);

        return new ViewController($postService);
    }
}