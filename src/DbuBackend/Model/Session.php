<?php

namespace DbuBackend\Model;

use DbuBackend\Model\User;

class Session
{
    protected $user;
    protected $isLoggedIn = true;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn();
    }
}
