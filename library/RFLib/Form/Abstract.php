<?php
/**
 * Simple base form class to provide model injection
 *
 * @category   RFLib
 * @package    RFLib_Form
 * @copyright  Copyright (c) 2010 Ricky Feng (ricky.feng@163.com)
 */
class RFLib_Form_Abstract extends Zend_Form
{
    
    /**
     * @var RFLib_Model_Interface
     */
    protected $_model;

    public function init()
    {
        $languageFile = LIBRARY_PATH .DS.'RFLib/Form/Language/zh_CN.php';
        if (file_exists($languageFile)) {
            $translate = new Zend_Translate('array',$languageFile, 'zh_CN');
            $this->setTranslator($translate);
        }
    }

    /**
     * Model setter
     * 
     * @param RFLib_Model_Abstract $model 
     */
    public function setModel(RFLib_Model_Abstract $model)
    {
        $this->_model = $model;
    }

    /**
     * Model Getter
     * 
     * @return RFlib_Model_Interface 
     */
    public function getModel()
    {
        return $this->_model;
    }
}