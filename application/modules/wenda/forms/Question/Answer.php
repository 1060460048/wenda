<?php
class Wenda_Form_Question_Answer extends RFLib_Form_Abstract
{
    public function init()
    {
        parent::init();
        
        $this->addElement('text','question_id',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(array('Int')),
            'required'      => true,
        ));
        
        $this->addElement('text','parent_id',array(
            'filters'       => array('StringTrim'),
            'validators'    => array(array('Int')),
            'required'      => false,
        ));
        
        $this->addElement('Textarea', 'content', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(10, 9999)) ),
            'required'      => true,
            'label'         => '内容'
        ));
        
        $this->addElement('submit', 'submit', array(
                'required'      => false,
                'ignore'        => true,
                'label'         => 'Submit'
        ));        
    }
}