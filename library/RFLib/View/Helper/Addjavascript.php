<?php
/**
 * Add javascript helper calss
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */
class RFLib_View_Helper_Addjavascript extends Zend_View_Helper_Abstract
{
    /**
     * 为网页增加javascript
     * 
     * @param string $file
     * @param string $position
     */
    public function addJavascript($file, $position = null)
    {
        $js =  $this->view->baseUrl() . '/js/'. $file;

        switch ($position){
            case 'first':
                $this->view->headScript()->prependFile($js);
                break;
            case 'last':
                $this->view->headScript()->offsetSetFile(100, $js);
                break;
            default :
                $this->view->headScript()->appendFile($js);         
        }
    }
}
?>
