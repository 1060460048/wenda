<?php
class RFLib_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        //enable cache for web page
        if (!RFLib_Core::getIsDeveloperMode()) {
            $manager = Zend_Controller_Front::getInstance()
                        ->getParam('bootstrap')
                        ->getPluginResource('cachemanager')
                        ->getCacheManager();
                        
            if ($manager->hasCache('webpage')) {
                $cache = $manager->getCache('webpage');
                $cache->start();
            }
        }
    }  
}