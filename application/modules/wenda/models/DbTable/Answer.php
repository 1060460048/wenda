<?php
class Wenda_Model_DbTable_Answer extends RFLib_Model_DbTable_Abstract
{
    protected $_name = 'Answer';
    
    public function findById($id)
    {
        $id = intval($id);
        
        return $this->find($id)->current();        
    }
    
    public function getUserNameById($id)
    {
        $select = $this->_db->select();
        $select->from('Answer as a','a.id');
        $select->joinLeft('user as u', 'u.id = a.user_id', array('name as user_name'));
        $select->where('a.id = ?', intval($id));
        $select->where('a.status = "A"');
        $row = $this->_db->fetchRow($select);
        if (isset($row['user_name'])) {
            return $row['user_name'];
        } else {
            return null;
        }
    }
    
	public function getRefanswerById($id)
	{
		$select = $this->_db->select();
		$select->from('Answer as a',array('a.id','a.content'));
		$select->joinLeft('user as u', 'u.id=a.user_id',array('name as user_name'));
		$select->where('a.id = ?',intval($id));
		$select->where('a.status = "A"');
		return $this->_db->fetchRow($select);
	}
	
    public function findAnswersByQuestionId($questionId,$page=null,$limit=10)
    {
        $select = $this->select();
        $select->from('Answer','id');
        $select->where('question_id = ?',intval($questionId));
        $select->where('parent_id = 0');
        $select->order('created_at');
        
		if (null !== $page) {
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('Answer', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);
			
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage(intval($limit))
		          	  ->setCurrentPageNumber(intval($page));
			return $paginator;
		}          
    }
    
    public function findByQuestionId($questionId)
    {
        $questionId = intVal($questionId);
        
        $select = $this->_db->select();
        $select->from('answer as a','*');
        $select->joinLeft('user as u', 'u.id = a.user_id',array('name as user_name','logo as user_logo'));
        $select->where('question_id = ?',$questionId);
        $select->order('created_at');
        
        return $this->_db->fetchAll($select);        
    }
    
    public function findByParentIds($ids)
    {
        $select = $this->_db->select();
        $select->from('answer as a','*');
        $select->joinLeft('user as u', 'u.id = a.user_id',array('name as user_name','logo as user_logo'));
        $select->where('a.id in (?)', $ids);
        $select->orWhere('a.parent_id in (?)',$ids);
        $select->order('a.created_at');
        
        return $this->_db->fetchAll($select);       
    }
    
    
    public function getRepliesById($id)
    {
        $id = intVal($id);
        
        $select = $this->_db->select();
        $select->from('answer as a','*');
        $select->joinLeft('user as u', 'u.id = a.user_id',array('name as user_name','logo as user_logo'));
        $select->where('parent_id = ?',$id);
        $select->order('created_at DESC');
        
        return $this->_db->fetchAll($select);            
    }
}