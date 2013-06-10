<?php

namespace DbuBackend\Controller;

use DbuBackend\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    /**
     * Index action
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->forward()->dispatch('DbuBackend\Controller\Login', array(
            'controller' => 'DbuBackend\Controller\Login',
            'action'     => 'login',
        ));
    }

    public function loginAction()
    {
        return new ViewModel(array(
            'url' => $this->url()->fromRoute('login', array('action' => 'post')),
        ));
    }

    /**
     * Post action
     */
    public function postAction()
    {
        /* @var $user \DbuBackend\Model\User */
        $user = $this->getServiceLocator()->get('DbuBackend\Model\User');
        $post = $this->getRequest()->getPost();

        $user->setLogin($post['login']);
        $user->verify($post['password']);

        $this->redirect()->toRoute('login');
    }

    public function logoutAction()
    {

        $this->redirect()->toRoute('login');
    }
}
