<?php

namespace User\Model\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Model\Service\UserModelService;

class UserModelServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new  UserModelService($entityManager);
    }
}