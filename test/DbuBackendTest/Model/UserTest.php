<?php

namespace DbuBackendTest\Model;

use DbuBackend\Model\User;
use DbuBackend\Module;
use DbuBackendTest\DbuTestCase;

class UserTest extends DbuTestCase
{
    protected $login = 'admin';
    protected $pass  = 'admin123';
    protected $hash  = '$2a$06$BMGcFWwnmS//gl3gUybfl.IvtUwCYIXeO23WQ2nlgcBdvtGI.wLBq';

    /**
     * @expectedException \DbuBackend\Model\Exception\RuntimeException
     */
    public function testThrowRuntimeExceptionWhenCallNotSettedResource()
    {
        $user = new User();
        $user->getResource();
    }

    /**
     * @expectedException \DbuBackend\Model\Exception\RuntimeException
     */
    public function testThrowRuntimeExceptionWhenCallNotSettedCrypt()
    {
        $user = new User();
        $user->getCrypt();
    }

    /**
     * @expectedException \DbuBackend\Model\Exception\RuntimeException
     */
    public function testThrowRuntimeExceptionWhenCallNotSettedLogin()
    {
        $user = new User();
        $user->getLogin();
    }

    public function testInvokeResourceWhenRetrieveNotSettedPasswordHash()
    {
        $resourceMock = $this->getMock('DbuBackend\Model\UserResource');
        $user = new User();
        $user->setResource($resourceMock)
            ->setCrypt($this->sm->get('Crypt'))
            ->setLogin($this->login);

        $resourceMock->expects($this->once())
            ->method('getPasswordHash')
            ->with($this->login)
            ->will($this->returnValue($this->hash));

        $this->assertTrue($user->verify($this->pass));
        $this->assertFalse($user->verify('anyfoobarstring'));
    }

    public function testUserLoginVerifying()
    {
        /* @var $user \DbuBackend\Model\User */
        $user = $this->sm->get('DbuBackend\Model\User');
        $user->setPasswordHash($this->hash);

        $this->assertTrue($user->verify($this->pass));
        $this->assertFalse($user->verify('anyfoobarstring'));
    }

    public function testCreateUserPasswordHash()
    {
        $opts = $this->sm->get('Application')->getConfig();
        $opts = isset($opts[Module::BACKEND_ROOT_CONFIG_NAME]['options'])
            ? $opts[Module::BACKEND_ROOT_CONFIG_NAME]['options']
            : array();

        $cryptMock = $this->getMock('Zend\Crypt\Password\Bcrypt', array(), array($opts));
        $cryptMock->expects($this->once())
            ->method('create')
            ->with($this->pass)
            ->will($this->returnValue($this->hash));

        $user = new User();
        $user->setCrypt($cryptMock);

        $user->create($this->pass);

        $this->assertEquals($this->hash, $user->getPasswordHash());
        $this->assertNotEquals('anyfoobarstring', $user->getPasswordHash());
    }
}
