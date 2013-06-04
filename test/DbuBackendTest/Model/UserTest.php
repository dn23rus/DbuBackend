<?php

namespace DbuBackendTest\Model;

use DbuBackendTest\Bootstrap;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testUserLoginVerifying()
    {
        $data = array(
            'pass' => 'admin123',
            'hash' => '$2a$06$BMGcFWwnmS//gl3gUybfl.IvtUwCYIXeO23WQ2nlgcBdvtGI.wLBq',
        );
        /* @var $user \DbuBackend\Model\User */
        $user = Bootstrap::getServiceManager()->get('DbuBackend\Model\User');
        $user->setPasswordHash($data['hash']);

        $this->assertTrue($user->verify($data['pass']));
        $this->assertFalse($user->verify($data['pass'] . 'any string'));
    }
}
