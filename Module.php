<?php

namespace DbuBackend;

use DbuBackend\Model\Session;
use DbuBackend\Model\User;
use DbuBackend\Model\UserResource;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container as SessionContainer;
use Zend\Session\SessionManager;


class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface,
    ServiceProviderInterface
{
    /**
     * Module specific config key
     */
    const BACKEND_ROOT_CONFIG_NAME = 'dbu-core';

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e event instance
     * @return \DbuBackend\Module
     */
    public function onBootstrap(EventInterface $e)
    {
        /* @var $e \Zend\Mvc\MvcEvent */
        $em = $e->getApplication()->getEventManager();
        $em->attach(MvcEvent::EVENT_ROUTE, array($this, 'attachAuthPlugin'), 2);
        return $this;
    }

    /**
     * Attach controller plugin
     *
     * @param MvcEvent $e mvc event instance
     * @return \DbuBackend\Module
     */
    public function attachAuthPlugin(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $sm         = $app->getServiceManager();
        $routeMatch = $sm->get('router')->match($sm->get('request'));
        if (null !== $routeMatch) {
            $app->getEventManager()->getSharedManager()->attach(
                'abstract.backend.controller',
                MvcEvent::EVENT_DISPATCH,
                function($e) use ($sm) {
                    /* @var $session \Zend\Session\SessionManager */
                    $session = $sm->get('Zend\Session\SessionManager');
                    $session->start();
                    $container = new SessionContainer('initialized');
                    if (!isset($container->init)) {
                        $session->regenerateId(true);
                        $container->init = 1;
                    }
                    $sm->get('ControllerPluginManager')
                        ->get('DbuBackend\Controller\Plugin\Auth')
                        ->setSessionContainer($container)
                        ->doAuthorization($e);
                }, 2
            );
        }

        return $this;
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
                'DbuBackend\Model\UserResource' => function($sm) use ($cnfKey) {
                    $cnf = $sm->get('Application')->getConfig();
                    $cnf = isset($cnf[$cnfKey]['users']) ? $cnf[$cnfKey]['users'] : array();
                    $userResource = new UserResource();
                    $userResource->setUsersCollection($cnf);
                    return $userResource;
                },
                'Crypt' => function($sm) use ($cnfKey) {
                    $cnf = $sm->get('Application')->getConfig();
                    $cnf = isset($cnf[$cnfKey]['options']) ? $cnf[$cnfKey]['options'] : array();
                    return new Bcrypt($cnf);
                },
                'Zend\Session\SessionManager' => function($sm) {
                    $cnf = $sm->get('Application')-> getConfig();
                    if (isset($cnf['session'])) {
                        $cnf = $cnf['session'];

                        // init session config
                        /* @var $sessionConfig \Zend\Session\Config\SessionConfig */
                        $sessionConfig = null;
                        if (isset($cnf['config'])) {
                            $class = isset($cnf['config']['class'])
                                ? $cnf['config']['class']
                                : 'Zend\Session\Config\SessionConfig';
                            $options = isset($cnf['config']['options']) ? $cnf['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }

                        // init session storage
                        $sessionStorage = null;
                        if (isset($cnf['storage'])) {
                            $class = $cnf['storage'];
                            $sessionStorage = new $class();
                        }

                        // init session save handler
                        $sessionSaveHandler = null;
                        if (isset($cnf['save_handler'])) {
                            $sessionSaveHandler = $sm->get($cnf['save_handler']);
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                        if (isset($cnf['validator'])) {
                            $chain = $sessionManager->getValidatorChain();
                            foreach ($cnf['validator'] as $validator) {
                                $validator = new $validator();
                                $chain->attach('session.validate', array($validator, 'isValid'));
                            }
                        }
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    SessionContainer::setDefaultManager($sessionManager);
                    return $sessionManager;
                }
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
}
