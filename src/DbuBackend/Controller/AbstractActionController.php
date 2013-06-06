<?php

namespace DbuBackend\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\View\Model\ViewModel;

abstract class AbstractActionController extends ZendAbstractActionController
{
    protected $eventIdentifier = 'abstract.backend.controller';
}
