<?php
class RFLib_Validate_UniqueEmail extends Zend_Validate_Abstract
{
    const EMAIL_EXISTS = 'emailExists';
	const ID_FIELD = 'id';
	
    protected $_messageTemplates = array(
            self::EMAIL_EXISTS => 'Email "%value%" already exists in our system',
    );

    public function __construct(RFLib_Model_Abstract $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        $currentUser = isset($context[self::ID_FIELD]) ? $this->_model->findById($context[self::ID_FIEL]) : null;
        $user = $this->_model->findByEmail($value, $currentUser);
        if (false === $user || null === $user) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}
