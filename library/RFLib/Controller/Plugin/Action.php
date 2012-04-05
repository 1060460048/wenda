<?php
/**
 * Application Action Plugin
 * 
 * This plugin uses the action stack to provide global
 * view segments.
 * 
 * @category   RFLib
 * @package    RFLib_Controller_Plugin
 */
class RFLib_Controller_Plugin_Action extends Zend_Controller_Plugin_Abstract 
{
    protected $_stack;

    public function routeShutdown(Zend_Controller_Request_Abstract $request) 
    {
        $stack = $this->getStack();
        // category menu
        $categoryRequest = new Zend_Controller_Request_Simple();
        $categoryRequest->setModuleName('wenda')
                        ->setControllerName('category')
                        ->setActionName('menu')
                        ->setParam('responseSegment', 'categoryMain');

        // push requests into the stack
        $stack->pushStack($categoryRequest);
    }

    public function getStack()
    {
        if (null === $this->_stack) {        
            $front = Zend_Controller_Front::getInstance();
            if (!$front->hasPlugin('Zend_Controller_Plugin_ActionStack')) {
                $stack = new Zend_Controller_Plugin_ActionStack();
                $front->registerPlugin($stack);
            } else {
                $stack = $front->getPlugin('ActionStack');
            }
            $this->_stack = $stack;
        }
        return $this->_stack;
    }
}