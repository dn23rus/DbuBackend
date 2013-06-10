<?php

namespace DbuBackend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Session\AbstractContainer;
use Zend\Http\Response;

class Auth extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $loginController  = 'DbuBackend\Controller\Login';
    protected $actions          = array('index', 'login', 'logout', 'post');

    /**
     * Set session container
     *
     * @var \Zend\Session\AbstractContainer
     * @param \DbuBackend\Controller\Plugin\Auth
     */
    protected $sessionContainer;

    public function setSessionContainer(AbstractContainer $container)
    {
        $this->sessionContainer = $container;
        return $this;
    }

    /**
     * Get session container
     *
     * @return \Zend\Session\AbstractContainer
     * @throws \Exception
     */
    public function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            // @todo lazy creation of session container?
            throw new \Exception('');
        }
        return $this->sessionContainer;
    }

    /**
     * Authorization
     *
     * @param MvcEvent $e mvc event instance
     * @return \DbuBackend\Controller\Plugin\Auth
     */
    public function doAuthorization(MvcEvent $e)
    {
        $params = $e->getRouteMatch()->getParams();
        $sesContainer = $this->getSessionContainer();
        if ($sesContainer['is_logged_in'] ||
            ($params['controller'] == $this->loginController && in_array($params['action'], $this->actions))
        ) {
            return $this;
        }
        return $this->redirectToLogin($e);
    }

    /**
     * Redirect to login page
     *
     * @param MvcEvent $e mvc event instance
     * @return \DbuBackend\Controller\Plugin\Auth
     */
    protected function redirectToLogin(MvcEvent $e)
    {
        $url = $e->getRouter()->assemble(array('action' => 'login'), array('name' => 'login'));
        $response = $e->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_302);
        $response->getHeaders()->addHeaderLine('Location', $url);
        $e->stopPropagation();
        return $this;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator service locator
     * @return \DbuBackend\Controller\Plugin\Auth
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
