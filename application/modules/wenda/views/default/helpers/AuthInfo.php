<?php
class Wenda_View_Helper_AuthInfo extends Zend_View_Helper_Abstract
{
    /**
     * @var Wenda_Service_Authentication
     */
    protected $_authService;
    
    /**
     *
     * @var array
     */
    protected $_user;

    /**
     * Get user info from the auth session
     *
     * @param string|null $info
     * @return null|Zend_View_Helper_AuthInfo
     */
    public function authInfo($info = null)
    {
        if (null === $this->_authService) {
            $this->_authService = RFLib_Core::getService('authentication');
        }

        if (null === $info) {
            return $this;
        }

        if (false === $this->isLogined()) {
            return null;
        }

        return $this->_authService->getIdentity()->$info;
    }

    /**
     * Check if we are logged in
     *
     * @return boolean
     */
    public function isLogined()
    {
        return $this->_authService->getAuth()->hasIdentity();
    }

    /**
     * Get auth user's ID
     * 
     * @return int | null
     */
    public function getId()
    {
        if (!$this->isLogined()) {
            return null;
        }
        if (null === $this->_user) {
            $this->_user = $this->_authService->getAuth()->getStorage()->read();
        }
        return $this->_user['id'];
    }
    
    /**
     * Get auth user's name
     * 
     * @return string | null
     */
    public function getName()
    {
        if (!$this->isLogined()) {
            return null;
        }
        if (null === $this->_user) {
            $this->_user = $this->_authService->getAuth()->getStorage()->read();
        }
        return $this->_user['name'];
    }
    
    /**
     * Get auth user's email address
     * 
     * @return string | null
     */
    public function getEmail()
    {
        if (!$this->isLogined()) {
            return null;
        }
        if (null === $this->_user) {
            $this->_user = $this->_authService->getAuth()->getStorage()->read();
        }
        return $this->_user['email'];        
    }
    
    /**
     * Get auth user's logo
     * 
     * @return string | null
     */
    public function getLogo()
    {
        if (!$this->isLogined()) {
            return null;
        }
        if (null === $this->_user) {
            $this->_user = $this->_authService->getAuth()->getStorage()->read();
        }
        return $this->_user['logo'];         
    }
}
