<?php

namespace DbuBackend;

return array(
    \DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME => array(
        'options' => array(
            'cost' => 6,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'DbuBackend\Controller\Console' => 'DbuBackend\Controller\ConsoleController',
            'DbuBackend\Controller\Login'   => 'DbuBackend\Controller\LoginController',
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
                        'controller' => 'DbuBackend\Controller\Login',
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
    'console' => array(
        'router' => array(
            'routes' => array(
                'dbu-backend-gethash' => array(
                    'options' => array(
                        'route' => 'dbu_backend gethash <pass>',
                        'defaults' => array(
                            'controller' => 'DbuBackend\Controller\Console',
                            'action' => 'gethash',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'DbuBackend\Controller\Plugin\Auth' => 'DbuBackend\Controller\Plugin\Auth',
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
