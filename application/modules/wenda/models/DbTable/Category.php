<?php

class Wenda_Model_DbTable_Category extends RFLib_Model_DbTable_Abstract
{

    protected $_name = 'Category';

    public function findById($id)
    {
        $id = intval($id);
        return $this->find($id)->current();
    }

    public function findByParentId($id)
    {
        $id = intval($id);

        $select = $this->select()
                        ->where('parent_id = ?', $id)
                        ->order('sort_order');

        return $this->fetchAll($select);
    }

    public function findParentById($id)
    {
        $id = intval($id);
        $row = $this->findById($id);
        return $this->find($row->parent_id)->current();
    }

}