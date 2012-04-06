<?php
class Wenda_Model_User extends RFLib_Model_Abstract
{
    const   PASSWORD_FIELD  = 'password';
    const   ID_FIELD        = 'id';
    const   PROTRAIT        = 'portrait.gif';

    /**
     * Get User Name by their id
     * @param int  $id
     * @return null|string
     */       
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
    * Get top users
    *
    * @param int $limit
    * @return array | null
    */
    public function getTopUsers($categoryId,$limit=20)
    {
        return $this->getTable('Question')->getTopUsers($categoryId,$limit);
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
    * Create user post data
    *
    * @param array $data
    * @param string $type 'Q'=Question 'A'=Answer or Reply
    * @return boolean
    */
    public function posted($data,$type='Q')
    {
        $auth = RFLib_Core::getService('authentication')->getAuth();
        if (!$auth->hasIdentity()) {
            return false;
        }
        $user = $auth->getStorage()->read();  

        if (!isset($data['post_id']) | !isset($data['post_category_id'])) {
            return false;
        }
        
        $date = new Zend_Date();
        
        $default = array(
            'user_id'=>$user['id'],
            'post_type'=>$type,
            'post_at'=>$date->getTimestamp()
        );
        $data = array_merge($data,$default);
    
        return $this->getTable('Userpost')->saveRow($data,null);
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
