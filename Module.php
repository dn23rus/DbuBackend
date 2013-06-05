<?php

namespace DbuBackend;

use DbuBackend\Model\Session;
use DbuBackend\Model\User;
use DbuBackend\Model\UserResource;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface,
    ServiceProviderInterface
{
    const BACKEND_ROOT_CONFIG_NAME = 'dbu-core';

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
     * Expected to return \Zend\ServiceManager\Config object or array to seed such an object.
     *
     * @return array
     */
    public function getServiceConfig()
    {
        $cnfKey = self::BACKEND_ROOT_CONFIG_NAME;
        return array(
            'factories' => array(
                'DbuBackend\Model\Session' => function($sm) {
                    return new Session($sm->get('DbuBackend\Model\User'));
                },
                'DbuBackend\Model\User' => function($sm) {
                    $user = new User();
                    $user->setCrypt($sm->get('Crypt'));
                    $user->setResource($sm->get('DbuBackend\Model\UserResource'));
                    return $user;
                },
                'DbuBackend\Model\UserResource' => function($sm) {
                    return new UserResource();
                },
                'Crypt' => function($sm) use ($cnfKey) {
                    $cnf = $sm->get('Application')->getConfig();
                    $cnf = isset($cnf[$cnfKey]['options']) ? $cnf[$cnfKey]['options'] : array();
                    return new Bcrypt($cnf);
                },
            ),
        );
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console console instance
     * @return array
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'dbu_backend gethash <pass>' => 'generate hash with random salt and print them',
        );
    }

    /**
     * Returns a string containing a banner text, that describes the module and/or the application.
     * The banner is shown in the console window, when the user supplies invalid command-line parameters or invokes
     * the application with no parameters.
     *
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     *
     * @param AdapterInterface $console
     * @return string
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return <<<BANNER
==================================================================
                       DbuBackend v0.1.0
==================================================================
BANNER;
    }
}
