<?php

namespace DbuBackendTest\Model;

use DbuBackendTest\DbuTestCase;

class UserTest extends DbuTestCase
{
    public function testUserLoginVerifying()
    {
        $data = array(
            'pass' => 'admin123',
            'hash' => '$2a$06$BMGcFWwnmS//gl3gUybfl.IvtUwCYIXeO23WQ2nlgcBdvtGI.wLBq',
        );
        /* @var $user \DbuBackend\Model\User */
        $user = $this->sm->get('DbuBackend\Model\User');
        $user->setPasswordHash($data['hash']);

        $this->assertTrue($user->verify($data['pass']));
        $this->assertFalse($user->verify($data['pass'] . 'any string'));
    }
}
