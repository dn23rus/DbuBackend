<?php

namespace DbuBackend\Model;

use Zend\Form\Annotation;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element;
use Zend\Form\FormInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;

/**
 * Class User
 *
 * @package DbuBackend\Model
 *
 * @Annotation\Name("Login form")
 * @Annotation\Attributes({"id":"login_form"})
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class User implements TranslatorAwareInterface
{
    /**
     * @var string
     *
     * @Annotation\Required(true)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text", "id":"login_user_name"})
     * @Annotation\Options({"label":"User name"})
     */
    protected $name;

    /**
     * @var string
     *
     * @Annotation\Required(true)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"NotEmpty"})
     * @Annotation\Attributes({"type":"password", "id":"login_user_password"})
     * @Annotation\Options({"label":"Password"})
     */
    protected $password;

    /**
     * @var string
     *
     * @Annotation\Exclude()
     */
    protected $passwordHash;

    /**
     * @var \Zend\Form\FormInterface
     *
     * @Annotation\Exclude()
     */
    protected $inputForm;

    /**
     * @var Translator
     *
     * @Annotation\Exclude()
     */
    protected $translator = null;

    /**
     * @var bool
     *
     * @Annotation\Exclude()
     */
    protected $translatorEnabled = true;

    /**
     * @var string
     *
     * @Annotation\Exclude()
     */
    protected $translatorTextDomain = 'default';

    /**
     * Set user name
     *
     * @param string $name user name
     * @return \DbuBackend\Model\User
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password password
     * @return \DbuBackend\Model\User
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password hash
     *
     * @param string $passwordHash password hash
     * @return \DbuBackend\Model\User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = (string) $passwordHash;
        return $this;
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set input form
     *
     * @param FormInterface $form
     * @return \DbuBackend\Model\User
     */
    public function setInputForm(FormInterface $form)
    {
        $this->inputForm = $form;
        return $this;
    }

    /**
     * Get input form.
     * Create if not exists
     *
     * @return FormInterface
     */
    public function getInputForm()
    {
        if (null === $this->inputForm) {
            $builder = new AnnotationBuilder();
            $form = $builder->createForm($this);

            $send = new Element('send');
            $submit = $this->hasTranslator() ? $this->getTranslator()->translate('Submit') : 'Submit';
            $send->setValue($submit);
            $send->setAttributes(array(
                'type'  => 'submit'
            ));

            $form->add(new Element\Csrf('security'));
            $form->add($send);

            $this->setInputForm($form);
        }
        return $this->inputForm;
    }

    /**
     * Sets translator to use in helper
     *
     * @param  Translator $translator  [optional] translator.
     *                                 Default is null, which sets no translator.
     * @param  string $textDomain  [optional] text domain
     *                                 Default is null, which skips setTranslatorTextDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        $this->translator = $translator;

        if (!is_null($textDomain)) {
            $this->setTranslatorTextDomain($textDomain);
        }

        return $this;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return !is_null($this->translator);
    }

    /**
     * Sets whether translator is enabled and should be used
     *
     * @param  bool $enabled [optional] whether translator should be used.
     *                       Default is true.
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true)
    {
        $this->translatorEnabled = $enabled;
        return $this;
    }

    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return $this->translatorEnabled;
    }

    /**
     * Set translation text domain
     *
     * @param  string $textDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->translatorTextDomain = $textDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->translatorTextDomain;
    }
}
