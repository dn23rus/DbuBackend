<?php

namespace DbuBackend\Model;

use DbuBackend\Model\User;
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
    protected $userCollection = array();

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
        return $this->userCollection;
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
        $users = $this->getUserCollection();
        // first check config/autoload/local.php
        if (isset($users[$login])) {
            $data = $users[$login];
            if (empty($data['password'])) {
                //todo implement throw exception
                throw new \Exception('');
            }
            if (isset($data['hashed']) && !$data['hashed']) {
                // used user model for create password hash
                return $this->getServiceLocator()->get('DbuBackend\Model\User')->getCrypt()->create($data['password']);
            }
            // assume that password is hashed
            return $data['password'];
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
