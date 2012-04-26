<?php
class Wenda_Model_DbTable_Question extends RFLib_Model_DbTable_Abstract
{
    protected $_name = 'Question';

    public function findById($id)
    {
        $select = $this->select();
        $select->where('id = ?', intval($id));
        $select->where('status = "A"');
        return $this->fetchRow($select);
    }
    
    public function getTopUsers($categoryId,$limit)
    {
        $limit = intval($limit);
        $categoryId = intval($categoryId);
        
        $select = $this->_db->select();
        $select->from('question as q',array('q.user_id','created_at'=>'max(q.created_at)'));
        $select->joinLeft('user as u','u.id = q.user_id', array('name as user_name','logo as user_logo'));
        $select->where('q.category_id = ?',$categoryId);
        $select->group('q.user_id');
        $select->order('created_at DESC');
        $select->limit($limit);
        return $this->_db->fetchAll($select);
    }    
    
    public function getByKeywords($keywords,$ignore,$limit)
    {
        $words = '';
        if (is_array($keywords)){
            $words = implode(' ',$keywords);
        }
        if (is_string($keywords)){
            $words = trim($keywords);
        }
        
        $select = $this->_db->select();
        $select->from('question_content as qc',array('qc.subject','qc.created_at'));
        $select->joinLeft('question as q', 'q.id = qc.question_id', array('q.token','q.answers','q.pageviews'));
        $select->where('MATCH (qc.keywords) AGAINST (?)', $words);
        if (isset($ignore)) {
            $select->where('q.token <> ?',$ignore);
        }
        $select->limit(intval($limit));
        return $this->_db->fetchAll($select);        
    }
 
    public function getAllByKeywords($keywords, $page=1,$limit=50)
    {
        $words = '';
        if (is_array($keywords)){
            $words = implode(' ',$keywords);
        }
        if (is_string($keywords)){
            $words = trim($keywords);
        }
        
        $select = $this->_questionSQLSelect();
        $select->where('MATCH (qc.keywords) AGAINST (?)', $words);
        $select->limit(intval($limit));
        return $this->_db->fetchAll($select);           
    }
    
	public function getByUserId($userId,$ignore,$limit)
	{
		$userId = intval($userId);
		$limit  = intval($limit);
		
		$select = $this->_db->select();
		$select->from('question as q', array('q.token','q.answers','q.pageviews','q.created_at'));
		$select->joinLeft('question_content as qc', 'qc.question_id = q.id','qc.subject');
        $select->where('q.user_id = ?', $userId);
        if (isset($ignore)) {
            $select->where('q.token <> ?',$ignore);
        }
		$select->order('q.created_at desc');
		$select->limit($limit);
		
		return $this->_db->fetchAll($select);
	}
    
    /**
     * 取问题资料通过用户输入的查询条件
     *
     * @param string $query
     * @param int $paged
     * @return array | null
     */
    public function getBySearchQuery($query,$paged=1,$limit=10)  
    {
        $words = explode(' ',$query);
        $select = $this->_questionSQLSelect(null, true);
        $sql = '';
        for($i=0;$i<count($words); $i++) {
            $sql .= "qc.subject like '%{$words[$i]}%'";
            $sql .= ($i + 1 < count($words)) ? ' or ' : '';
        }
        for($i=0;$i<count($words); $i++) {
            $sql .= " or qc.keywords like '%{$words[$i]}%'";
        }
        $select->where($sql);
        $select->limit(intval($limit));
        
        // if (null !== $paged) {
            // $adapter = new Zend_Paginator_Adapter_DbSelect($select);
            // $count = clone $select;
            // $count->reset(Zend_Db_Select::COLUMNS);
            // $count->reset(Zend_Db_Select::FROM);
            // $count->from('Question as q', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
            // $adapter->setRowCount($count);
//             
            // $paginator = new Zend_Paginator($adapter);
            // $paginator->setItemCountPerPage($limit)
                      // ->setCurrentPageNumber((int) $paged);
            // return $paginator;
        // }            
        
        
        return $this->_db->fetchAll($select);
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
        $select->from('question as q', array('q.token','q.category_id'));
        $select->joinLeft('question_content as qc', 'qc.question_id = q.id','qc.subject');
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
        $select = $this->_questionSQLSelect(null,true);
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
    
    private function _questionSQLSelect($categoryId=null,$needContent=false)
    {
        $select = $this->_db->select();
        $select->from('question as q','*');
        if ($needContent) {
            $select->joinLeft('question_content as qc', 'qc.question_id = q.id','*');
        } else {
            $select->joinLeft('question_content as qc', 'qc.question_id = q.id',array('qc.subject','qc.keywords'));
        }
        $select->joinleft('user as u','u.id = q.user_id',array('u.name as user_name','u.logo as user_logo'));
        $select->joinleft('user as u2','u2.id = q.last_answer_user_id', array('u2.name as last_answer_user_name'));
        $select->where('q.status = "A"');
        if (null !== $categoryId) {
            $select->where('q.category_id = ?', intval($categoryId));
        }
        return $select;
    }
}