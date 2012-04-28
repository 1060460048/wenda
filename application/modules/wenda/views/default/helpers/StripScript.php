<?php
class Wenda_View_Helper_Stripscript extends Zend_View_Helper_Abstract
{
    public function stripScript($string)
    {
        $pattern="/<script.*script {0,}>/i";
        $replacement = '';
        return preg_replace($pattern, $replacement, $string);        
    }
}