<?php
require_once 'QuestionTable.php';

class Wenda_Model_DbTable_Question extends RFLib_Model_DbTable_Abstract
{
    protected $_name = 'Question';
    protected $_rowClass = 'Wenda_Model_DbTable_QuestionTable';

    public function findById($id)
    {
        $select = $this->select();
        $select->where('id = ?', intval($id));
        $select->where('status = "A"');
        return $this->fetchRow($select);
    }
    
    
    /**
     * 取问题资料by ID
     *
     * @param int $id
     * @return Zend_Db_Table_Row_Abstract | null
     */
    public function getShortDataById($id)
    {
        $select = $this->_db->select();
        $select->from('question as q', 'token');
        $select->joinLeft('question_content as qc', 'qc.question_id = q.id','subject');
        $select->where('q.id = ?', intval($id));

        return $this->_db->fetchRow($select);
    }

    /**
     * 查找问题记录by token字段
     * @param string $token
     * @return Zend_Db_Table_Row_Abstract
     */
    public function findByToken($token)
    {
        $select = $this->_questionSQLSelect();
        $select->where('token = ?',$token);
        return $this->_db->fetchRow($select);
    }

    /**
     * 查找没有解决的问题
     * @param int $categoryId
     * @param int $limit
     * @return array|null
     */
    public function unsolves($categoryId=null,$paged=null,$limit=30)
    {
        $select = $this->_questionSQLSelect($categoryId);
        $select->where('q.finish = ?', 'N');
        $select->order('q.created_at desc');

		if (null !== $paged) {
			$adapter = new Zend_Paginator_Adapter_DbSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('Question as q', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);
			
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage($limit)
		          	  ->setCurrentPageNumber((int) $paged);
			return $paginator;
		}        
        
        return $this->_db->fetchAll($select);
    }

    /**
     * 查找已解决的问题
     * @param int $categoryId
     * @param int $limit
     * @return array|null
     */
    public function solves($categoryId=null,$paged=null,$limit=30)
    {
        $select = $this->_questionSQLSelect($categoryId);
        $select->where('q.finish = ?', 'Y');
        $select->order('q.created_at desc');

		if (null !== $paged) {
			$adapter = new Zend_Paginator_Adapter_DbSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('Question as q', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);
			
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage($limit)
		          	  ->setCurrentPageNumber((int) $paged);
			return $paginator;
		}        
        
        return $this->_db->fetchAll($select);
    }    
    
    /**
     * 查找没有回答的问题
     * @param int $categoryId
     * @param int $limit
     * @return array|null
     */
    public function zeros($categoryId=null,$paged=null,$limit=30)
    {
        $select = $this->_questionSQLSelect($categoryId);
        $select->where('q.answers = 0');
        $select->order('q.created_at desc');

		if (null !== $paged) {
			$adapter = new Zend_Paginator_Adapter_DbSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('Question as q', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);
			
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage($limit)
		          	  ->setCurrentPageNumber((int) $paged);
			return $paginator;
		}        
        
        return $this->_db->fetchAll($select);
    }    
    
    
    /**
     * 设置问题的Token
     * @param int $id
     * @return Zend_Db_Statement_Interface
     */
    public function setToken($id)
    {
        $id = intval($id);
        $token = $this->_hash($id);
        //查找有没有相同的记录
        if ($this->findByToken($token)) {
            $token = $this->_hash(md5($id));
        }
        return $this->_db->query(
            'UPDATE question SET token = ? WHERE id = ?',
            array($token, $id)
        );
    }

    /**
     * Get character map
     *
     * @param string $data
     * @return string
     */
    private function _hash($data) {
        static $map = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $hash = crc32($data) + 0x100000000;
        $str = "";
        do {
            $str = $map[31 + ($hash % 31)] . $str;
            $hash /= 31;
        } while ($hash >= 1);
        return $str;
    }
    
    private function _questionSQLSelect($categoryId=null)
    {
        $select = $this->_db->select();
        $select->from('question as q','*');
        $select->joinLeft('question_content as qc', 'qc.question_id = q.id','*');
        $select->joinleft('user as u','u.id = q.user_id',array('u.name as user_name','u.logo as user_logo'));
        if (null !== $categoryId) {
            $select->where('q.category_id = ?', intval($categoryId));
        }
        return $select;
    }
}