<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'DbuBackend\Controller\Admin' => 'DbuBackend\Controller\AdminController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'DbuBackend\Form\AdminLogin' => 'DbuBackend\Form\AdminLogin',
        ),
        'shared' => array(
            'DbuBackend\Model\User' => false,
        ),
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DbuBackend\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'login' => __DIR__ . '/../view/default',
        ),
    ),
    'session' => array(
        'config' => array(
            'options' => array(
                'name'                  => 'backend',
                'remember_me_seconds'   => 86400,
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'ru_RU',
        'translation_file_patterns' => array(
            array(
                'type'     => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ),
        ),
    ),
);
