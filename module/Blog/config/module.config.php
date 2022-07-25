<?php

namespace Blog;

use Blog\Controller\CommentController;
use Blog\Controller\Factory\CommentControllerFactory;
use Blog\Controller\Factory\PostControllerFactory;
use Blog\Controller\PostController;
use Blog\Model\Repository\Factory\PostRepositoryFactory;
use Blog\Model\Repository\PostRepository;
use Blog\Model\Service\BlogModelService;
use Blog\Model\Service\Factory\BlogModelServiceFactory;
use Blog\Plugin\LolaPlugin;
use Blog\Service\CommentService;
use Blog\Service\Factory\CommentServiceFactory;
use Blog\Service\Factory\PostServiceFactory;
use Blog\Service\PostService;
use Blog\View\Helper\LolaHelper;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'post' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/post',
                    'defaults' => [
                        'controller' => PostController::class,
                        'action' => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/:id',
                            'defaults' => [
                                'action' => 'view',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'add-comment' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/add-comment',
                                    'defaults' => [
                                        'controller' => CommentController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/edit/:id',
                            'defaults' => [
                                'action'     => 'edit',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'action'     => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            PostController::class => PostControllerFactory::class,
            CommentController::class => CommentControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            BlogModelService::class => BlogModelServiceFactory::class,

            PostService::class => PostServiceFactory::class,
            CommentService::class => CommentServiceFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases' => [
            'lolaPlugin' => LolaPlugin::class
        ],
        'factories' => [
            LolaPlugin::class => InvokableFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'LolaHelper' => LolaHelper::class
        ],
        'factories' => [
            LolaHelper::class => InvokableFactory::class,
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