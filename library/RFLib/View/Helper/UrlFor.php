<?php
/**
 * URL Helper
 * 
 * @example		urlFor('controller/action?param=value'[,string module][,boolean reset][,boolean encode])
 *				urlFor('@routeName?param=value'[,string module][,boolean reset][,boolean encode])
 * @copyright	Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license     New BSD License
 */
class RFLib_View_Helper_Urlfor extends Zend_View_Helper_Abstract
{
    public function urlFor()
    {
        $arguments = func_get_args();

        if (count($arguments) <=0 ) {
            throw new Zend_View_Exception('URL define mistake!');
        }

        list($route, $params) = RFLib_Utility_Parse::urlToParam($arguments[0]);
        $params['module'] = isset($arguments[1]) ? 
                            $arguments[1] :
                            Zend_Controller_Front::getInstance()->getRequest()->getModuleName();

        $reset      = isset($arguments[2]) ? $arguments[2] : true;
        $encode     = isset($arguments[3]) ? $arguments[3] : true;

        return $this->view->url($params, $route, $reset, $encode);
    }
}
?>