<?php
/**
 * RFLib
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ricky.feng@163.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Wenda to newer
 * versions in the future. If you wish to customize Wenda for your
 * needs please refer to https://www.github.com/rickyfeng/wenda.git for more information.
 *
 * @category    RFLib
 * @package     RFLib_Controller
 * @copyright   Copyright (c) 2010 Ricky Feng (ricky.feng@163.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * View plugin class
 * 
 * @author      Ricky Feng <ricky.feng@163.com>
 * @version     $Id: View.php 2011-06-12 19:08:44Z Ricky Feng $ * 
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
