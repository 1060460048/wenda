<?php
class Wenda_Model_DbTable_Questioncontent extends RFLib_Model_DbTable_Abstract
{
    protected $_name = 'question_content';
    protected $_primary = 'question_id';

    public function findByQuestionId($id)
    {
        $id = intval($id);
        $select = $this->select();
        $select->where('question_id = ?', $id);
        return $this->fetchRow($select);
    }
}