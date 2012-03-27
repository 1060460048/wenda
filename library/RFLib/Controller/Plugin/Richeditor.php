<?php
class RFLib_Controller_Plugin_Richeditor extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        
        //get view object
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;

        //check who need richeditor
        if (!isset($view->{$moduleName}['richeditor'])) {
            return;
        }
        $needs = explode(';', $view->{$moduleName}['richeditor']);
        if (!in_array($controllerName , $needs)) {
            return;
        }
        
        //add kideditor js
        $view->addJavascript('kindeditor/kindeditor-min.js');
        $view->addJavascript('wenda/qcontent.js');
        
        //add syntax highlighter js
        $jsPath = $view->baseUrl() . '/js';        
        $view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shCore.css');
        $view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shThemeDefault.css');
        $view->addJavascript('syntax-highlighter/brush.js');
        $view->headScript()->offsetSetScript(9999,
             "SyntaxHighlighter.config.clipboardSwf = '". $view->baseUrl()."/js/syntax-highlighter/scripts/clipboard.swf';" .
             " SyntaxHighlighter.all();"
        );
    }
}