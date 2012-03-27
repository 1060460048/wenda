<?php
/**
 * View plugin class
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */
class RFLib_Controller_Plugin_Richeditor extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        //get view object
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;

        //add kideditor js
        $this->view->addJavascript('kindeditor/kindeditor-min.js');
        $this->view->addJavascript('wenda/qcontent.js');

        //add syntax highlighter js
        $this->view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shCore.css');
        $this->view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shThemeDefault.css');
        $this->view->addJavascript('syntax-highlighter/brush.js');
        $this->view->headScript()->offsetSetScript(9999,
             "SyntaxHighlighter.config.clipboardSwf = '". $this->view->baseUrl()."/js/syntax-highlighter/scripts/clipboard.swf';" .
             " SyntaxHighlighter.all();"
        );        
        
    }
}