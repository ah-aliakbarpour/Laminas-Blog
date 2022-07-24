<?php

namespace Blog;

use Blog\Controller\AddController;
use Blog\Controller\CommentController;
use Blog\Controller\DeleteController;
use Blog\Controller\EditController;
use Blog\Controller\Factory\AddControllerFactory;
use Blog\Controller\Factory\CommentControllerFactory;
use Blog\Controller\Factory\DeleteControllerFactory;
use Blog\Controller\Factory\EditControllerFactory;
use Blog\Controller\Factory\IndexControllerFactory;
use Blog\Controller\Factory\ViewControllerFactory;
use Blog\Controller\IndexController;
use Blog\Controller\ViewController;
use Blog\Model\Service\BlogModelService;
use Blog\Model\Service\Factory\BlogModelServiceFactory;
use Blog\Service\CommentService;
use Blog\Service\Factory\CommentServiceFactory;
use Blog\Service\Factory\PostServiceFactory;
use Blog\Service\PostService;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'post' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/post',
                    'defaults' => [
                        'controller' => IndexController::class,
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
                                'controller' => ViewController::class,
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
                                'controller' => AddController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/edit/:id',
                            'defaults' => [
                                'controller' => EditController::class,
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
                                'controller' => DeleteController::class,
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
            IndexController::class => IndexControllerFactory::class,
            ViewController::class => ViewControllerFactory::class,
            AddController::class => AddControllerFactory::class,
            EditController::class => EditControllerFactory::class,
            DeleteController::class => DeleteControllerFactory::class,

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