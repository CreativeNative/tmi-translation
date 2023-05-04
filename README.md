# tmi-translation
Database backed up translation module for German, English and Italien language. Requires Laminas and Doctrine.

Laminas translations are created from database entries and saved in php array files.

- \data\language\de_DE.php
- \data\language\en_US.php
- \data\language\it_IT.php

## Configuration
```
return [
    'doctrine' => [
        'orm_autoload_annotations' => true,
        'cache'                    => [
            'apcu_tmi' => [
                'class'     => ApcuCache::class,
                'namespace' => 'apcu_tmi',
            ],
            'array'    => [
                'class'     => ArrayCache::class,
                'namespace' => 'array',
            ],
        ],
        'configuration'            => [
            'orm_default' => [
                'metadata_cache'   => 'apcu_tmi',
                'query_cache'      => 'apcu_tmi',
                'result_cache'     => 'apcu_tmi',
                'hydration_cache'  => 'apcu_tmi',
                'driver'           => 'orm_default',
                'generate_proxies' => false,
                'proxy_dir'        => __DIR__ . '/../../data/cache/proxy',
                'proxy_namespace'  => 'terramia',
            ]
        ],
        'driver'                   => [           
            'tmi_translation' => [
                'class' => AnnotationDriver::class,
                'cache' => 'apcu_tmi',
                'paths' => [
                    __DIR__ . '/../../vendor/creativenative/tmi-translation/src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [                   
                    'TmiTranslation\Entity' => 'tmi_translation'
                ]
            ]
        ]
    ]
];

```