<?php
class Wenda_Service_Authentication
{
    /**
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter;

    /**
     * @var Storefront_Model_User
     */
    protected $_userModel;

    /**
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Construct
     *
     * @param null|Wenda_Model_User $userModel
     */
    public function __construct(Wenda_Model_User $userModel = null)
    {
        $this->_userModel = (null === $userModel) ? RFLib_Core::getModel('User') : $userModel;
    }

    /**
     * Authenticate a user
     *
     * @param  array $data Matched pair array containing email/password
     * @return boolean
     */
    public function authenticate($data)
    {
        $adapter = $this->getAuthAdapter($data);
        $auth    = $this->getAuth();
        $result  = $auth->authenticate($adapter);

        if (!$result->isValid()) {
            return false;
        }

        if ($data['save-login']) {
            Zend_Session::rememberMe();
        } else {
            Zend_Session::forgetMe();
        }

        $user = $this->_userModel->findByEmail($data['email']);
        $auth->getStorage()->write($user->toArray());
        
        return true;
    }

	/**
	 * Get the auth object
	 * 
	 * @return Zend_Auth
	 */
    public function getAuth()
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }

    public function getIdentity()
    {
        $auth = $this->getAuth();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        return false;
    }

    /**
     * Clear any authentication data
     */
    public function logout()
    {
        $this->getAuth()->clearIdentity();
    }

    /**
     * Set the auth adpater.
     *
     * @param Zend_Auth_Adapter_Interface $adapter
     */
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_authAdapter = $adapter;
    }

    /**
     * Get and configure the auth adapter
     *
     * @param  array $value Array of user credentials
     * @return Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter($values)
    {
        if (null === $this->_authAdapter) {
            $salt = RFLib_Config::getPasswordSalt();
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table_Abstract::getDefaultAdapter(),
                'user',
                'email',
                'password',
                'MD5(CONCAT(?,"' . $salt . '"))'
            );
            $this->setAuthAdapter($authAdapter);
            $this->_authAdapter->setIdentity($values['email']);
            $this->_authAdapter->setCredential($values['password']);
        }
        return $this->_authAdapter;
    }
}
