<?php
class RFLib_View_Helper_Substr extends Zend_View_Helper_Abstract
{
    public function substr($string, $limit = 100, $charset='UTF-8', $after = '...') 
    {
    	$string = trim($string);
    	
        if (null == $string) {
            return '';
        }
        if (mb_strlen($string) <= $limit) {
            return $string;
        }
        return mb_substr($string, 0, $limit, $charset) . $after;
    }

}