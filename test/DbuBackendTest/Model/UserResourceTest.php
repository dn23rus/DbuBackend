<?php

namespace DbuBackendTest\Model;

use DbuBackend\Module;
use DbuBackendTest\DbuTestCase;

class UserResourceTest extends DbuTestCase
{
    public function testRetrieveNotHashedPasswordFromUserModel()
    {
        $data = array(
            'admin' => array(
                'password' => 'admin123',
                'hashed' => false,
            )
        );
        $cnf = $this->sm->get('Application')->getConfig();
        $cnf = $cnf[Module::BACKEND_ROOT_CONFIG_NAME]['options'];
        $cryptMock = $this->getMock('Zend\Crypt\Password\Bcrypt', array(), array($cnf));
//        $userMock = $this->getMock('DbuBackend\Model\User', array(), array($cryptMock));

        $this->sm->setAllowOverride(true)
            ->setService('Crypt', $cryptMock);

        $cryptMock->expects($this->once())
            ->method('create')
            ->with($data['admin']['password']);

        $user = $this->sm->get('DbuBackend\Model\User');


        $model = $this->sm->get('DbuBackend\Model\UserResource');
        $model->setUserCollection($data);
        $model->getPasswordHash('admin');
    }
}
