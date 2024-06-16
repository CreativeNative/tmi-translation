<?php

namespace TmiTranslation;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use TmiTranslation\Controller\TranslationBackController;

return [
    'doctrine'      => [
        'driver' => [
            'tmi_translation' => [
                'class' => AnnotationDriver::class,
                'cache' => 'filesystem',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'TmiTranslation\Entity' => 'tmi_translation'
                ]
            ]
        ]
    ],
    'controllers'  => [
        'factories' => [
            TranslationBackController::class => Controller\Factory\TranslationBackControllerFactory::class,
        ],
    ],
    'router'       => [
        'routes' => [
            'translation' => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/translation',
                    'defaults' => [
                        'controller' => TranslationBackController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'create' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'controller' => TranslationBackController::class,
                                'action'     => 'create',
                            ],
                        ],
                    ],
                    'edit'   => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => TranslationBackController::class,
                                'action'     => 'edit',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => TranslationBackController::class,
                                'action'     => 'delete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\TranslationForm::class         => Form\Factory\TranslationFormFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => include __DIR__ . '/template_map.config.php',
    ]
];
