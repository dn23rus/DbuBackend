<?php

namespace DbuBackend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\AbstractContainer;

class Auth extends AbstractPlugin
{
    /**
     * @var \Zend\Session\AbstractContainer
     */
    protected $sessionContainer;

    public function setSessionContainer(AbstractContainer $container)
    {
        $this->sessionContainer = $container;
        return $this;
    }

    public function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            // @todo lazy creation of session container?
            throw new \Exception('');
        }
        return $this->sessionContainer;
    }

    protected $isLoggedIn = false;

    public function doAuthorization(MvcEvent $e)
    {
//        $routeMatch = $e->getRouteMatch();
//
//        \Zend\Debug\Debug::dump($routeMatch->getParams());die;
//
//        if (!$this->isLoggedIn) {
//            $router = $e->getRouter();
//        }
        return $this;
    }
}
