<?php

class Wenda_Form_User_Base extends RFLib_Form_Abstract
{

    public function init()
    {
        parent::init();

        // add path to custom validators
        $this->addElementPrefixPath(
                'RFLib_Validate',
                LIBRARY_PATH . '/RFLib/Validate/',
                'validate'
        );

        //add email element
        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(6, 30)),
                array('EmailAddress')),
            'required' => true,
            'label' => '邮箱'
        ));

        //add display name element
        $this->addElement('text', 'name', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('Alnum'),
                array('StringLength', true, array(4, 12)),
                array('UniqueName', false, array(RFLib_Core::getModel('User'))),
            ),
            'required' => true,
            'label' => '姓名'
        ));

        //add password element
        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(4, 128))),
            'required' => true,
            'label' => '密码'
        ));

        //add password verification element
        $this->addElement('password', 'password2', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                'PasswordVerification',
            ),
            'required' => true,
            'label' => '密码确认',
        ));

        //add gender element
        $this->addElement('radio', 'gender', array(
            'required' => true,
            'label' => '姓别',
            'MultiOptions' => array('boy', 'girl')
        ));

        //add city element
        $this->addElement('text', 'city', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(1, 20))),
            'required' => false,
            'label' => '居住地区',
        ));

        //add userId element
        $this->addElement('hidden', 'id', array(
            'filters' => array('StringTrim'),
            'required' => true,
        ));

        //add submit button element
        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Submit'
        ));
    }

}