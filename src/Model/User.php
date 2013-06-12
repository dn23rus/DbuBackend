<?php

namespace DbuBackend\Model;

/**
 * Class User
 *
 * @package DbuBackend\Model
 */
class User
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * Set user name
     *
     * @param string $name user name
     * @return \DbuBackend\Model\User
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password password
     * @return \DbuBackend\Model\User
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password hash
     *
     * @param string $passwordHash password hash
     * @return \DbuBackend\Model\User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = (string) $passwordHash;
        return $this;
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
}
