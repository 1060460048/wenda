<?php

class Wenda_Model_Answer extends RFLib_Model_Abstract
{

    const ROOT_ID = 0;

    public function findById ($id)
    {
        return $this->getTable('Answer')->findById($id);
    }

    public function getUserNameById($id)
    {
       return $this->getTable('Answer')->getUserNameById($id);    
    }
    
    public function getByQuestionId ($questionId, $page, $limit)
    {
        return $this->getTable('Answer')->findAnswersByQuestionId($questionId, $page, $limit);
    }

    public function getAllByIds ($ids)
    {
        if (empty($ids)) {
            return null;
        }
            
        $result = $this->getTable('Answer')->findByParentIds($ids);
        
        $rows = array();
        foreach ($result as $row) {
            $rows[$row['id']] = $row;
        }
        
        // Merge children to parent
        $t = array();
        foreach ($rows as $id => $item) {
            if (isset($item['parent_id'])) {
                if (self::ROOT_ID == $item['parent_id']) {
                    $rows[$item['parent_id']][$item['id']] = &$rows[$item['id']];
                } else {
                    $rows[$item['parent_id']]['replies'][$item['id']] = &$rows[$item['id']];
                }
                // record who need unset
                $t[] = $id;
            }
        }
        foreach ($t as $u) {
            unset($rows[$u]);
        }
        $rows = isset($rows[0]) ? $rows[0] : array();
        
        // 逆向排序 回复
        $arr = array();
        foreach ($rows as $key => $value) {
            if (isset($value['replies'])) {
                krsort($value['replies']);
            }
            $arr[$key] = $value;
        }
        return $arr;
    }

    public function getRepliesById ($id)
    {
        return $this->getTable('Answer')->getRepliesById($id);
    }

    public function getQuestionIdById ($id)
    {
        if ($row = $this->findById($id)) {
            return $row->question_id;
        }
        return null;
    }

    public function create ($data, $form = null)
    {
        if (null === $form) {
            return false;
        }
        if ($this->_save($form, $data)) {
            if ($form instanceof Wenda_Form_Question_Answer) {
                return RFLib_Core::getModel('Question')->setAnswers(
                        $data['question_id']);
            }
            return true;
        } else {
            return false;
        }
    
    }

    private function _save ($form, $data, $defaults = array())
    {
        if (! isset($data['user_id'])) {
            $auth = RFLib_Core::getService('authentication')->getAuth();
            if (! $auth->hasIdentity()) {
                return false;
            }
            $user = $auth->getStorage()->read();
            $data['user_id'] = $user['id'];
        }
        
        if (! $form->isValid($data)) {
            return false;
        }
        
        $defaults['ip_address'] = ip2long(
                Zend_Controller_Front::getInstance()->getRequest()->getClientIp());
        
        // apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }
        
        $date = new Zend_Date();
        $data['updated_at'] = $date->getTimestamp();
        
        $row = array_key_exists('id', $data) ? $this->findById($data['id']) : null;
        
        if (null === $row) {
            $date = new Zend_Date();
            $data['created_at'] = $date->getTimestamp();
        }
        
        return $this->getTable('Answer')->saveRow($data, $row);
    }
}