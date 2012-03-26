<?php

class Wenda_Form_Question_Reply extends RFLib_Form_Abstract
{
    public function init()
    {
        parent::init();

        $this->addElement('hidden', 'parent_id', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(array('Int')),
            'required'      => true,
        ));

        $this->addElement('Textarea', 'rp_content', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(1, 200)) ),
            'required'      => true,
            'label'         => '内容'
        ));
    }
}
