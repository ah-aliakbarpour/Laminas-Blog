<?php

namespace User;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use User\Controller\AuthController;
use User\Controller\Factory\AuthControllerFactory;
use User\Model\Service\Factory\UserModelServiceFactory;
use User\Model\Service\UserModelService;
use User\Service\Factory\AuthServiceFactory;
use User\Service\AuthService;

return [
    'router' => [
        'routes' => [
            'register' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'profile' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/profile',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'profile',
                    ],
                ],
            ],

        ],
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            UserModelService::class => UserModelServiceFactory::class,
            AuthService::class => AuthServiceFactory::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Model/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];