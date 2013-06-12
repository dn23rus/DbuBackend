<?php

namespace DbuBackend\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class AdminLogin
 *
 * @package DbuBackend\Form
 */
class AdminLogin extends Form
{
    /**
     * Constructor
     *
     * @param string|null $name    form name
     * @param array       $options options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct('Login form');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'User name',
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'login_user_name',
            ),
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim')
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'required' => true,
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'login_user_password',
            ),
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim')
            ),
        ));
        $this->add(new Element\Csrf('security'));
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
            ),
        ));
    }
}
