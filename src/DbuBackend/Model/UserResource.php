<?php

namespace DbuBackend\Model;

use DbuBackend\Model\User;
use DbuBackend\Module;
use DbuBackend\Model\UserResourceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserResource implements ServiceLocatorAwareInterface, UserResourceInterface
{
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator service locator instance
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
        $cnf = $this->getServiceLocator()->get('Application')->getConfig();
        $cnf = isset($cnf[Module::BACKEND_ROOT_CONFIG_NAME]) ? $cnf[Module::BACKEND_ROOT_CONFIG_NAME] : array();

        // first check config/autoload/local.php
        if (isset($cnf['users']) && isset($cnf['users'][$login])) {
            $cnf = $cnf['users'][$login];
            if (empty($cnf['password'])) {
                //todo implement throw exception
                throw new \Exception('');
            }
            if (isset($cnf['hashed']) && !$cnf['hashed']) {
                // used user model for create password hash
                return $this->getServiceLocator()->get('DbuBackend\Model\User')->getCrypt()->create($cnf['password']);
            }
            // assume that password is hashed
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
