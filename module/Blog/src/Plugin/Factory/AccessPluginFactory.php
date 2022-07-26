<?php

namespace Blog\Plugin\Factory;

use Blog\Plugin\AccessPlugin;
use Blog\Service\PostService;
use Laminas\Mvc\Controller\Plugin\Params;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AccessPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccessPlugin
    {
        $postService = $container->get(PostService::class);

        return new AccessPlugin($postService);
    }
}