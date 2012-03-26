<?php

class Wenda_Model_DbTable_Keyword extends RFLib_Model_DbTable_Abstract
{

    protected $_name = 'Keyword';

    public function findByCateAndValue($categoryId, $value)
    {
        $categoryId = intval($categoryId);

        $select = $this->select();
        $select->where('category_id = ?',$categoryId);
        $select->where('value = ?',$value);
        return $this->fetchRow($select);
    }

    public function getWordByValue($value)
    {
       $select = $this->select();
       $select->from('Keyword','word');
       $select->where('value = ?', $value);
       $select->limit(1);
       $row = $this->fetchRow($select);
       
       if ($row) {
           return $row->word;
       } else {
           return ucwords($value);
       }
    }

    public function getHots($categoryId,$limit=50)
    {
        $categoryId = intval($categoryId);

        $select = $this->select();
        $select->where('category_id = ?', $categoryId);
        $select->order('scores DESC');
        $select->limit($limit);
        return $this->fetchAll($select)->toArray();
    }
}
