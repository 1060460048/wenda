<?php
class Wenda_Form_Question_Base extends RFLib_Form_Abstract
{
    public function init()
    {
        parent::init();

        $this->addElement('hidden', 'id', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(array('Int')),
            'required'      => true,
        ));

        $this->addElement('hidden', 'category_id', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(array('Int')),
            'required'      => true,
        ));

        $this->addElement('text','subject',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(8,50)),
            ),
            'required'      => true,
            'label'         => '标题'
        ));

        $this->addElement('text','tag1',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(2,50)),
                array('Alnum'),
            ),
            'required'      => false,
            'label'         => '标签1'
        ));

        $this->addElement('text','tag2',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(2,50)),
                array('Alnum'),
            ),
            'required'      => false,
            'label'         => '标签2'
        ));

        $this->addElement('text','tag3',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(2,50)),
                array('Alnum'),
            ),
            'required'      => false,
            'label'         => '标签3'
        ));

        $this->addElement('text','tag4',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(2,50)),
                array('Alnum'),
            ),
            'required'      => false,
            'label'         => '标签4'
        ));

        $this->addElement('text','tag5',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength',true,array(2,50)),
                array('Alnum'),
            ),
            'required'      => false,
            'label'         => '标签5'
        ));

        $this->addElement('Textarea', 'content', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(5, 9999)) ),
            'required'      => true,
            'label'         => '内容'
        ));

        $this->addElement('text','notify_method',array(
            'checked'       => true,
            'required'      => false,
            'label'         => '邮件通知'
        ));

        //add submit button element
        $this->addElement('submit', 'submit', array(
            'required'      => false,
            'ignore'        => true,
            'label'         => 'Submit'
        ));

    }
}