<?php

namespace DbuBackend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    /**
     * Index action
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(array(
            'form' => $this->getServiceLocator()->get('DbuBackend\Form\AdminLogin'),
        ));
    }

    /**
     * Login action
     *
     * @return void
     */
    public function loginAction()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* @var $auth \Zend\Authentication\AuthenticationService */
            /* @var $adapter \DbuBackend\Authentication\Adapter */
            $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $adapter = $auth->getAdapter();
            $adapter
                ->setUsername($request->getPost('login'))
                ->setPassword($request->getPost('password'));

            $result = $auth->authenticate();

            if ($result->isValid()) {
                //...
            } else {
                //...
            }
        }
    }

    /**
     * Logout action
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService')->clearIdentity();
        // @todo redirect
    }

    /**
     * Forbid action
     *
     * @return array|ViewModel
     */
    public function forbidAction()
    {

    }
}
