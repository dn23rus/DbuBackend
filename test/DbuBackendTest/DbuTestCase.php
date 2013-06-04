<?php

namespace DbuBackendTest;

use PHPUnit_Framework_TestCase;

class DbuTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * @var array
     */
    protected $cnf;

    public function setUp()
    {
        $this->sm  = clone Bootstrap::getServiceManager();
        $this->cnf = Bootstrap::getConfig();
        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->sm);
        unset($this->cnf);
        parent::tearDown();
    }
}
