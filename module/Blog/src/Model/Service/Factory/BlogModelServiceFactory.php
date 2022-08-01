<?php

namespace Blog\Model\Service\Factory;

use Blog\Model\Service\BlogModelService;
use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BlogModelServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): BlogModelService
    {
        $entityManager = $container->get(EntityManager::class);

        return new BlogModelService($entityManager);
    }
}