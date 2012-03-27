<?php
/**
 * ZFDebug resource
 *
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */

class RFLib_Application_Resource_ZFDebug
    extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        //get ini file options
        $iniOptions = $this->getOptions();

        //set ZFDebug to autoload
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader -> registerNamespace('ZFDebug');

        //initialized Front Controller
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('frontController');
        $frontController = $bootstrap->getResource('frontController');

        if (RFLib_Core::getIsDeveloperMode() && trim($iniOptions['enable'])) {
            //set ZFDebug options
            $options = array(
                    'plugins' => array(
                            'Variables',
                            'File' => array('basePath' => APPLICATION_PATH .'/..'),
                            'Memory',
                            'Time',
                            'Registry',
                            'Exception'
                    ),
            );
            
            //add cache option if specified
            if ($bootstrap->hasPluginResource('cache')){
                $bootstrap->bootstrap('cache');
                $cache = $bootstrap->getPluginResource('cache')->getBackend();
                $options['plugins']['Cache']['backend'] = $cache;
            }

            // add db option if specified
            if ($bootstrap->hasPluginResource('db')) {
                $bootstrap->bootstrap('db');
                $db = $bootstrap->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }
            	
            $debug = new ZFDebug_Controller_Plugin_Debug($options);
            $frontController->registerPlugin($debug);
        }
    }
}