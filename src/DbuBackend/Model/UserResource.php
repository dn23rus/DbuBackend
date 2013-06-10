<?php

namespace DbuBackend\Model;

use DbuBackend\Model\Exception;
use DbuBackend\Model\UserResourceInterface;
use DbuBackend\Module;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserResource implements ServiceLocatorAwareInterface, UserResourceInterface
{
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $collection;

    /**
     * Set users collection
     *
     * @param array $collection users collection
     * @return \DbuBackend\Model\UserResource
     */
    public function setUsersCollection(array $collection)
    {
        $this->collection = $collection;
        return $this;
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
        if (!isset($this->collection[$login])) {
            // @todo implement db usage
            return null;
        }
        $data = $this->collection[$login];
        if (empty($data['password'])) {
            // return null because we cant use empty password
            return null;
        }
        if (isset($data['hashed']) && !$data['hashed']) {
            // used user model for create password hash
            return $this->getServiceLocator()->get('DbuBackend\Model\User')->getCrypt()->create($data['password']);
        }
        // assume that password is hashed
        return $data['password'];
    }

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
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
