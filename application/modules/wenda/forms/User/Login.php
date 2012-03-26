<?php

class Wenda_Form_User_Login extends RFLib_Form_Abstract
{

    public function init()
    {
        //add email element
        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(6, 30)),
                array('EmailAddress')),
            'required' => true,
            'label' => 'Email',
        ));

        //add password element
        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 20))),
            'required' => true,
            'label' => 'Password'
        ));

        //add save cookie checkbox element
        $this->addElement('checkbox', 'save-login', array(
            'attribs' => array('checked' => 'checked', 'value' => 1),
            'label' => 'Save login status',
        ));

        //add submit button element
        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Login'
        ));
    }

}

