<?php
class RFLib_View_Helper_Substr extends Zend_View_Helper_Abstract
{
    public function substr($string, $limit = 100, $after = '...') 
    {
    	$string = trim($string);
    	
        if (null == $string) {
            return '';
        }
        if (strlen($string) <= $limit) {
            return $string;
        }

        if (function_exists('mb_substr')) {
            return mb_substr($string, 0, $limit, 'UTF-8') . $after;
        } else {
            return substr($string, 0, $limit) . $after;
        }
    }

}