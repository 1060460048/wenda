<?php
class Wenda_Form_Question_Create extends Wenda_Form_Question_Base
{
    public function init()
    {
        parent::init();

        $this->removeElement('id');
        $this->getElement('submit')->setLabel('发表问题');
    }
}