<?php

namespace DbuBackend\Model;

use DbuBackend\Module;
use DbuBackend\Model\UserResourceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserResource implements ServiceLocatorAwareInterface, UserResourceInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $userCollection;

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
     * Set users collection
     *
     * @param array $collection users collection
     * @return \DbuBackend\Model\UserResource
     */
    public function setUserCollection(array $collection)
    {
        $this->userCollection = $collection;
        return $this;
    }

    /**
     * Get users collection
     *
     * @return array
     */
    public function getUserCollection()
    {
        if (null === $this->userCollection) {
            $cnf = $this->getServiceLocator()->get('Application')->getConfig();
            $this->userCollection = isset($cnf[Module::BACKEND_ROOT_CONFIG_NAME]['users'])
                ? $cnf[Module::BACKEND_ROOT_CONFIG_NAME]['users']
                : array();
        }
        return $this->userCollection;
    }

    /**
     * Returns stored password hash for given login
     *
     * @param string $login login
     * @return string|null
     * @throws \Exception
     */
    public function getPasswordHash($login)
    {
        $users = $this->getUserCollection();
        $pass  = null;
        if (isset($users[$login])) {
            $data = $users[$login];
            if (empty($data['password'])) {
                //todo implement throw exception
                throw new \Exception('');
            }
            if (isset($data['hashed']) && !$data['hashed']) {
                // used user model for create password hash
                // @todo remove using service locator
                $pass = $this->getServiceLocator()->get('DbuBackend\Model\User')->getCrypt()->create($data['password']);
            }
            // assume that password is hashed
            $pass = $data['password'];
        }
        return $pass;
    }
}
