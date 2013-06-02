<?php

namespace DbuBackend\Model;

interface UserResourceInterface
{
    /**
     * Returns stored password hash for given login
     *
     * @param string $login login
     * @return string
     */
    public function getPasswordHash($login);
}
