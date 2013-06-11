<?php

namespace DbuBackend;

use DbuBackend\Authentication\Adapter as AuthAdapter;
use DbuBackend\Model\User;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Session\Container as SessionContainer;
use Zend\Session\SessionManager;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    const BACKEND_ROOT_CONFIG_NAME = 'dbu-backend';

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DbuBackend\Model\User' => function($sm) {
                    return new User();
                },
                'Zend\Authentication\AuthenticationService' => function($sm) {
                    $storage = new SessionStorage('backend', null, $sm->get('Zend\Session\SessionManager'));
                    $adapter = new AuthAdapter();
                    return new AuthenticationService($storage, $adapter);
                },
                'Zend\Session\SessionManager' => function($sm) {
                    $cnf = $sm->get('Application')-> getConfig();
                    if (isset($cnf['session'])) {
                        $cnf = $cnf['session'];

                        // init session config
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
                },
            ),
        );
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
