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
        return new ViewModel(array(
            'content' => 'Login controller index action'
        ));
    }

    public function loginAction()
    {

    }

    public function loginPostAction()
    {

    }
}
