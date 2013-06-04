<?php

namespace DbuBackend\Controller;

use Zend\Console\ColorInterface as Color;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class ConsoleController extends AbstractActionController
{
    /**
     * Execute the request
     *
     * @param MvcEvent $e mvc event instance
     * @return mixed
     * @throws \RuntimeException
     */
    public function onDispatch(MvcEvent $e)
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            if (!$request instanceof ConsoleRequest){
                throw new \RuntimeException('You can only use this action from a console!');
            }
        }
        return parent::onDispatch($e);
    }

    /**
     * Get hash action
     *
     * @return void
     */
    public function gethashAction()
    {
        /**
         * @var $console \Zend\Console\Adapter\AbstractAdapter
         * @var $user \DbuBackend\Model\User
         */

        $pass = $this->getRequest()->getParam('pass');
        if (empty($pass)) {
            $console->writeLine('Password cannot be empty', Color::RED);
            return;
        }
        $user = $this->getServiceLocator()->get('DbuBackend\Model\User');
        $hash = $user->create($pass)->getPasswordHash();

        $console = $this->getServiceLocator()->get('Console');
        $console->write("Hash for given password ($pass): ", Color::LIGHT_CYAN);
        $console->writeLine($hash, Color::LIGHT_WHITE);
    }
}
