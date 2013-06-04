<?php

namespace DbuBackend\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\View\Model\ViewModel;

abstract class AbstractActionController extends ZendAbstractActionController
{
    /**
     * Execute the request
     *
     * @param MvcEvent $e
     * @return mixed|\Zend\Http\Response
     * @throws \Zend\Mvc\Exception\DomainException
     */
//    public function onDispatch(MvcEvent $e)
//    {
//        if ($this->requireRedirect($e->getRouteMatch())) {
//            return $this->redirect()->toRoute('');
//        }
//        return parent::onDispatch($e);
//    }

    /**
     * Check if require redirect to login page
     *
     * @param RouteMatch $routeMatch routeMatch
     * @return bool
     */
    protected function requireRedirect(RouteMatch $routeMatch)
    {
        /* @var $session \DbuBackend\Model\Session */
        $session = $this->getServiceLocator()->get('DbuBackend\Model\Session');
        return !$session->isLoggedIn();
    }

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function($e) use ($controller) {
            /* @var $e \Zend\Mvc\MvcEvent */
            if ($controller->requireRedirect($e->getRouteMatch())) {
                return $controller->redirect()->toRoute('');
            }
        }, 100);
    }


}
