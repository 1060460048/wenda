<?php
class Wenda_Model_DbTable_QuestionTable extends RFLib_Model_DbTable_Item_Abstract
{
    public function setAnswers($value)
    {
        $this->getRow()->answers = $value;
    }
}