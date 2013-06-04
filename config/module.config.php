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
            'DbuBackend\Controller\Console' => 'DbuBackend\Controller\ConsoleController'
        ),
    ),
    'routers' => array(

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
);
