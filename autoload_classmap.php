<?php
return array(
    'DbuBackend\Controller\AbstractActionController'         => __DIR__ . '/src/DbuBackend/Controller/AbstractActionController.php',
    'DbuBackend\Controller\BackendController'                => __DIR__ . '/src/DbuBackend/Controller/BackendController.php',
    'DbuBackend\Controller\LoginController'                  => __DIR__ . '/src/DbuBackend/Controller/LoginController.php',
    'DbuBackend\Controller\ConsoleController'                => __DIR__ . '/src/DbuBackend/Controller/ConsoleController.php',
    'DbuBackend\Controller\Plugin\Auth'                      => __DIR__ . '/src/DbuBackend/Controller/Plugin/Auth.php',
    'DbuBackend\Model\User'                                  => __DIR__ . '/src/DbuBackend/Model/User.php',
    'DbuBackend\Model\UserResourceInterface'                 => __DIR__ . '/src/DbuBackend/Model/UserResourceInterface.php',
    'DbuBackend\Model\UserResource'                          => __DIR__ . '/src/DbuBackend/Model/UserResource.php',
    'DbuBackend\Model\Session'                               => __DIR__ . '/src/DbuBackend/Model/Session.php',
    'DbuBackend\Model\Exception\RuntimeException'            => __DIR__ . '/src/DbuBackend/Model/Exception/RuntimeException.php',
    'DbuBackend\Module'                                      => __DIR__ . '/Module.php',
    'DbuBackendTest\Bootstrap'                               => __DIR__ . '/test/Bootstrap.php',
    'DbuBackendTest\DbuTestCase'                             => __DIR__ . '/test/DbuBackendTest/DbuTestCase.php',
    'DbuBackendTest\Controller\AbstractActionControllerTest' => __DIR__ . '/test/DbuBackendTest/Controller/AbstractActionControllerTest.php',
    'DbuBackendTest\Model\UserTest'                          => __DIR__ . '/test/DbuBackendTest/Model/UserTest.php',
    'DbuBackendTest\Model\UserResourceTest'                  => __DIR__ . '/test/DbuBackendTest/Model/UserResourceTest.php',
);
