<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'DbuBackend\Controller\Admin' => 'DbuBackend\Controller\AdminController',
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
);
