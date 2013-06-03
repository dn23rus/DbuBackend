<?php

namespace DbuBackend;

use Zend\Crypt\Password\Bcrypt;
use DbuBackend\Model\User;
use DbuBackend\Model\UserResource;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    const BACKEND_ROOT_CONFIG_NAME  = 'dbu-core';

    /**
     * Configs
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Class autoload config
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array
     */
    public function getServiceConfig()
    {
        $cnfKey = self::BACKEND_ROOT_CONFIG_NAME;
        return array(
            'factories' => array(
                'DbuBackend\Model\User' => function($sm) use ($cnfKey) {
                    /* @var $sm \Zend\ServiceManager\ServiceManager */
                    $config = $sm->get('Application')->getConfig();
                    $crypt  = new Bcrypt($config[$cnfKey]['options']);
                    $user   = new User($crypt);
                    $user->setResource($sm->get('DbuBackend\Model\UserResource'));
                    return $user;
                },
                'DbuBackend\Model\UserResource' => function($sm) {
                    return new UserResource();
                },
            ),

        );
    }
}
