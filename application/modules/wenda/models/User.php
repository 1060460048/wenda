<?php
class Wenda_Model_User extends RFLib_Model_Abstract
{
    const   PASSWORD_FIELD  = 'password';
    const   ID_FIELD        = 'id';
    const   PROTRAIT        = 'portrait.gif';
    
    
    public function getNameById($id)
    {
        $row = $this->getTable('User')->getNameById($id);
        if ($row) {
            return $row['name'];
        } else {
            return null;
        }
    }
    
    /**
     * Find User by their id
     * @param int  $id
     * @return null|Zend_Db_Table_Row
     */
    public function findById($id)
    {
        return $this->getTable('User')->findById($id);
    }

    /**
     * Find User by their name
     * @param string $name
     * @param Wenda_Table_Row_User $ignore
     * @return null|Zend_Db_Table_Row
     */

    public function findByName($name, $ignore = null)
    {
        return $this->getTable('User')->findByName($name,$ignore);
    }

    /**
     * Find User by their email
     * @param string $email
     * @param Wenda_Table_Row_User $ignore
     * @return null|Zend_Db_Table_Row
     */
    public function findByEmail($email, $ignore = null)
    {
        return $this->getTable('User')->findByEmail($email,$ignore);
    }
		
    /**
     * Register a new user
     * @param array $post
     * @return false|int
     */
    public function register($post,$form=null)
    {
        if (null === $form) {
            $form = RFLib_Core::getForm('userRegister');
        }
        return $this->_save($form, $post, array('group_id' => 10,'logo'=>self::PROTRAIT));
    }    
    
  /**
     * Save the data to db
     *
     * @param  Zend_Form $form The Validator
     * @param  array     $info The data
     * @param  array     $defaults Default values
     * @return false|int
     */
    protected function _save($form, $info, $defaults=array())
    {
        if (!$form->isValid($info)) {
            return false;
        }

        // get filtered values
        $data = $form->getValues();

        // password hashing
        if (array_key_exists(self::PASSWORD_FIELD, $data) && '' == $data[self::PASSWORD_FIELD]) {
            unset($data[self::PASSWORD_FIELD]);
        } elseif(isset($data[self::PASSWORD_FIELD])) {
            $salt = RFLib_Config::getPasswordSalt();
            $data[self::PASSWORD_FIELD] = md5($data[self::PASSWORD_FIELD] . $salt);
        }

        // apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = array_key_exists('id',$data) ? $this->findById($data['id']) : null;

        return $this->getTable('User')->saveRow($data, $user);
    }    
}
