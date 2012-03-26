<?php

class Wenda_Model_DbTable_User extends RFLib_Model_DbTable_Abstract
{

    protected $_name = 'User';

    public function getNameById($id)
    {
        $id = intval($id);
        $select = $this->_db->select();
        $select->from('user', 'name');
        $select->where('user.id =?',$id);
        $select->where('status="A"');
        return $this->_db->fetchRow($select);
    }
    
    public function findById($id)
    {
        $id = intval($id);
        return $this->find($id)->current();
    }

    public function findByName($name, $ignore = null)
    {
        $select = $this->select();
        $select->where('name = ?', $name);

        if (null !== $ignore) {
            $select->where('name != ?', $ignore->name);
        }

        return $this->fetchRow($select);
    }

    public function findByEmail($email, $ignoreUser)
    {
        $select = $this->select();
        $select->where('email = ?', $email);

        if (null !== $ignoreUser) {
            $select->where('email != ?', $ignoreUser->email);
        }
        return $this->fetchRow($select);
    }

}
