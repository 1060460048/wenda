<?php
/**
 * View plugin class
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */
class RFLib_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        //get variables
        $moduleName = $request->getModuleName();
        $basePath = Zend_Controller_Front::getInstance()->getModuleDirectory();
 
        //get view object
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
 
        $template = isset($view->{$moduleName}['template']) ? $view->{$moduleName}['template'] : 'default';
        $templatePath = $basePath . DS . 'views' . DS . $template;        

        //set view
        $view->addHelperPath('RFLib/View/Helper', 'RFLib_View_Helper');        
        $view->addHelperPath($templatePath . DS . 'helpers', ucfirst(strtolower($moduleName)) . '_View_Helper');
        
        if (null != $view->baseUrl){
            $view->getHelper('BaseUrl')->setBaseUrl($view->baseUrl);
        }

        //Set layout
        Zend_Layout::startMvc(array('layoutPath' => $templatePath . DS . 'layouts'));
        Zend_Layout::getMvcInstance()->setLayout('layout');
        
        //set module base path
        $view->setScriptPath($templatePath . DS .'scripts');
        
        // set the content type and language
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=' . $view->charset);
        $view->headMeta()->appendName('description', $view->description);
        $view->headMeta()->appendName('keywords', $view->keyword);
        $view->headLink(array('rel'=>'shortcut icon','type'=>'image/x-icon','href'=>$view->baseUrl() . '/favicon.ico'));

        // setting a separator string for segments:
        $view->headTitle()->setSeparator(' - ');

        // setting the site in the title
        $view->headTitle($view->title);

        //view some assign variables
        $view->assign('siteName', $view->title);
        $view->assign('skinUrl', $view->baseUrl() . '/skin/' . $moduleName . '/' . $template);
    }
}
?>
