<?php
/**
 * Extend Zend_Application_Resource_Translate
 * Auto select translate file by locale
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */

class RFLib_Application_Resource_Translate extends Zend_Application_Resource_Translate
{
    public function init()
    {
        $options = $this->getOptions();
        
        //reset content options     
        if (isset($options['locale']) 
                && isset($options['content'])
                && isset($options['fileExt'])) {
                    
            //get locale data 
            $session = new Zend_Session_Namespace('RFLib');
            if ($session->locale) {
                $locale = $session->locale;
            } else {
                $locale = $options['locale'];
            }
            
            $options['content'] = $options['content'] . DS . $locale . $options['fileExt'];            
        }
        
        $this->setOptions($options);
                
        parent::init();
    }
}
?>
