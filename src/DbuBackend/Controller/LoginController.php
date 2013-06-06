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
            'url' => $this->url()->fromRoute('login', array('action' => 'post')),
        ));
    }

    /**
     * Post action
     */
    public function postAction()
    {
        $this->redirect()->toRoute('login');
    }
}
