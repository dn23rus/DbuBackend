<?php

namespace DbuBackend\Model;

use DbuBackend\Model\User;
use DbuBackend\Model\UserResourceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserResource implements ServiceLocatorAwareInterface, UserResourceInterface
{
    protected $serviceLocator;

    protected $userModel;

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
     * Load data
     *
     * @param User $user user
     * @return \DbuBackend\Model\UserResource
     */
    public function load(User $user)
    {
        $cnf = $this->getServiceLocator()->get('Application')->getConfig();
        $cnf = isset($cnf[\DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME])
            ? $cnf[\DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME]
            : array();
        if (isset($cnf['users'])) {
            // load from local file system (config/autoload/local.php)
            if (isset($cnf['users'][$user->getLogin()])) {
                $cnf = $cnf['users'][$user->getLogin()];
                if (!isset($cnf['password'])) {
                    //todo throw exception
                }
                if (isset($cnf['hashed']) && !$cnf['hashed']) {
                    $user->create($cnf['password']);
                } else {
                    $user->setPasswordHash($cnf['password']);
                }
            } else {
                $this->loadFromDb($user);
            }
        } else {
            $this->loadFromDb($user);
        }
        return $this;
    }

    /**
     * Load data from db
     *
     * @param User $user user
     * @return \DbuBackend\Model\UserResource
     */
    public function loadFromDb(User $user)
    {
        // @todo implement loading from db
        $user->setPasswordHash('');
        return $this;
    }

}
