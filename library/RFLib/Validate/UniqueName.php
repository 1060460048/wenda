<?php
class RFLib_Validate_UniqueName extends Zend_Validate_Abstract
{
    const NAME_EXISTS = 'nameExists';
	const ID_FIELD = 'id';

    protected $_messageTemplates = array(
            self::NAME_EXISTS => 'User name "%value%" already exists in our system',
    );

    public function __construct(RFLib_Model_Abstract $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $currentUser = isset($context[self::ID_FIELD]) ? 
        				$this->_model->findById($context[self::ID_FIELD]) : null;
        $user = $this->_model->findByName($value, $currentUser);

        if (false === $user || null === $user) {
            return true;
        }

        $this->_error(self::NAME_EXISTS);
        return false;
    }
}
