<?php

namespace DbuBackend\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Adapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * Set username
     *
     * @param $username
     * @return \DbuBackend\Authentication\Adapter
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param $password
     * @return \DbuBackend\Authentication\Adapter
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $code = Result::SUCCESS;
        if (!$this->getUsername()) {
            $code = Result::FAILURE_IDENTITY_AMBIGUOUS;
        }
        return new Result($code, $this->getUsername());
    }
}
