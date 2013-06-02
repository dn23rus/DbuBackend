<?php

namespace DbuBackend\Model;

use DbuBackend\Model\UserResourceInterface;

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

    public function __construct(\Zend\Crypt\Password\PasswordInterface $crypt)
    {
        $this->crypt = $crypt;
    }

    public function setResource(UserResourceInterface $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return \DbuBackend\Model\UserResourceInterface
     */
    public function getResource()
    {
        if (null === $this->resource) {
            // @todo throw exception
        }
        return $this->resource;
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
     * Return login
     *
     * @return string
     */
    public function getLogin()
    {
        if (null === $this->login) {
            //@todo throw exception
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
            $this->getResource()->getPasswordHash($this->getLogin());
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

    /**
     * @return \Zend\Crypt\Password\PasswordInterface
     */
    public function getCrypt()
    {
        return $this->crypt;
    }
}
