<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
{
    protected function _initDefaultModuleAutoloader()
    {
        Zend_Debug::dump('install bootstrap');
        
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Wenda',
            'basePath'  => APPLICATION_PATH . '/modules/wenda',
        ));
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Install',
            'basePath'  => APPLICATION_PATH . '/modules/install',
        ));        
	}
    
    protected function _initInstalled()
    {
        $installed = RFLib_Core::isInstalled();
        $baseUrl   = RFLib_Core::getBaseUrl();
        if ($installed) {
            header('location: '.$baseUrl);
        }
    }    
}    
