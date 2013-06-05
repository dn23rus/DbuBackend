<?php

namespace DbuBackendTest\Model;

use DbuBackend\Module;
use DbuBackendTest\DbuTestCase;

class UserResourceTest extends DbuTestCase
{
    protected $login = 'admin';
    protected $pass  = 'admin123';
    protected $hash  = '$2a$06$BMGcFWwnmS//gl3gUybfl.IvtUwCYIXeO23WQ2nlgcBdvtGI.wLBq';

    public function testRetrieveNotHashedPasswordFromUserModel()
    {
        $opts = $this->sm->get('Application')->getConfig();
        $opts = isset($opts[Module::BACKEND_ROOT_CONFIG_NAME]['options'])
            ? $opts[Module::BACKEND_ROOT_CONFIG_NAME]['options']
            : array();

        $collection = array(
            'admin' => array(
                'password' => 'admin123',
                'hashed' => false,
            )
        );

        $userMock  = $this->getMock('DbuBackend\Model\User');
        $cryptMock = $this->getMock('Zend\Crypt\Password\Bcrypt', array(), array($opts));

        $this->sm->setAllowOverride(true) //@todo resolve problem with setting services
            ->setService('DbuBackend\Model\User', $userMock)
            ->setService('Crypt', $cryptMock);

        $userMock->expects($this->once())
            ->method('getCrypt')
            ->will($this->returnValue($cryptMock));

        $cryptMock->expects($this->once())
            ->method('create')
            ->with($this->pass)
            ->will($this->returnValue($this->hash));

        $resource = $this->sm->get('DbuBackend\Model\UserResource');
        $resource->setUserCollection($collection);

        $this->assertEquals($this->hash, $resource->getPasswordHash($this->login));
    }
}
