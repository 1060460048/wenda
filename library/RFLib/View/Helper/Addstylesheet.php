<?php
/**
 * Add style sheet helper calss
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */
class RFLib_View_Helper_Addstylesheet extends Zend_View_Helper_Abstract
{
    /**
     * 加入CSS样式资料
     * 
     * @param string $file
     * @param string $position
     * @param string $media
     * @param boolean $conditionalStylesheet
     */
    
    public function addStylesheet($file, $position = null, $media = 'screen',
        $conditionalStylesheet = true)
    {
        $css = $this->view->skinUrl .'/css/' . $file;

        switch ($position){
            case 'first':
                $this->view->headLink()->prependStylesheet(
                    $css, $media, $conditionalStylesheet
                );
                break;
            case 'last':
                $this->view->headLink()->offsetSetStylesheet(
                    100, $css, $media, $conditionalStylesheet
                );
                break;
            default:
                $this->view->headLink()->appendStylesheet(
                    $css, $media, $conditionalStylesheet
                );                
        }
    }
}
?>
