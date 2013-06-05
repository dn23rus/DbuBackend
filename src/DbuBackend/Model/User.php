<?php

namespace DbuBackend\Model;

use DbuBackend\Model\Exception;
use DbuBackend\Model\UserResourceInterface;
use Zend\Crypt\Password\PasswordInterface;

class User
{
    /**
     * @var \DbuBackend\Model\UserResourceInterface
     */
    protected $resource;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * @var \Zend\Crypt\Password\PasswordInterface
     */
    protected $crypt;

    /**
     * Set resource instance
     *
     * @param UserResourceInterface $resource resource instance
     * @return \DbuBackend\Model\User
     */
    public function setResource(UserResourceInterface $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Get resource instance
     *
     * @return UserResourceInterface
     * @throws Exception\RuntimeException
     */
    public function getResource()
    {
        if (null === $this->resource) {
            throw new Exception\RuntimeException(sprintf('Require set resource before call %s', __METHOD__));
        }
        return $this->resource;
    }

    /**
     * Set crypt
     *
     * @param PasswordInterface $crypt crypt
     * @return \DbuBackend\Model\User
     */
    public function setCrypt(PasswordInterface $crypt)
    {
        $this->crypt = $crypt;
        return $this;
    }

    /**
     * Get crypt
     *
     * @return PasswordInterface
     */
    public function getCrypt()
    {
        if (null === $this->crypt) {
            throw new Exception\RuntimeException(sprintf('Require set crypt before call %s', __METHOD__));
        }
        return $this->crypt;
    }

    /**
     * Set login
     *
     * @param string $login login
     * @return \DbuBackend\Model\User
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Get login
     *
     * @return string
     * @throws Exception\RuntimeException
     */
    public function getLogin()
    {
        if (null === $this->login) {
            throw new Exception\RuntimeException(sprintf('Require set login before call %s', __METHOD__));
        }
        return $this->login;
    }

    /**
     * Set password hash
     *
     * @param string $passwordHash password hash
     * @return \DbuBackend\Model\User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    /**
     * Return password hash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        if (null === $this->passwordHash) {
            $this->passwordHash = $this->getResource()->getPasswordHash($this->getLogin());
        }
        return $this->passwordHash;
    }

    /**
     * Verify password
     *
     * @param string $password password
     * @return bool
     */
    public function verify($password)
    {
        return $this->getCrypt()->verify($password, $this->getPasswordHash());
    }

    /**
     * Create password hash
     *
     * @param string $password password
     * @return \DbuBackend\Model\User
     */
    public function create($password)
    {
        return $this->setPasswordHash($this->getCrypt()->create($password));
    }
}
