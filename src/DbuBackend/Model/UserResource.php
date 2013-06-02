<?php

namespace DbuBackend\Model;

use DbuBackend\Model\User;
use DbuBackend\Model\UserResourceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserResource implements ServiceLocatorAwareInterface, UserResourceInterface
{
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \DbuBackend\Model\UserResource
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Returns stored password hash for given login
     *
     * @param string $login login
     * @return string
     * @throws \Exception
     */
    public function getPasswordHash($login)
    {
        /* @var $app \Zend\Mvc\Application */
        $cnf = $this->getServiceLocator()->get('Application')->getConfig();
        $cnf = isset($cnf[\DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME])
            ? $cnf[\DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME] : array();

        if (isset($cnf['users']) && isset($cnf['users'][$login])) {
            // load from local file system (config/autoload/local.php)
            $cnf = $cnf['users'][$login];
            if (empty($cnf['password'])) {
                //todo implement throw exception
                throw new \Exception('');
            }
            if (isset($cnf['hashed']) && !$cnf['hashed']) {
                /* @var $user \DbuBackend\Model\User */
                // used user model for create password hash
                $user = $this->getServiceLocator()->get('DbuBackend\Model\User');
                $user->create($cnf['password']);
                return $user->getPasswordHash();
            }
            return $cnf['password'];
        }
        return $this->getHashFromDb($login);
    }

    /**
     * Returns password hash from db
     *
     * @param string $login login
     * @return string
     */
    public function getHashFromDb($login)
    {
        //@TODO implement retrieving password hash from db
        return '';
    }
}
