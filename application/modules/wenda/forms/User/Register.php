<?php
class Wenda_Form_User_Register extends Wenda_Form_User_Base
{

    public function init()
    {
        parent::init();

        $this->removeElement('id');
        $this->getElement('submit')->setLabel('Register');
    }

}
