<?php
class RFLib_Application_Resource_LoaderCache extends Zend_Application_Resource_ResourceAbstract
{
    const CACHE_FOLDER   = 'cache';
    const CACHE_FILE     = 'loaderCache.php';
    
    public function init()
    {
        if (!RFLib_Core::getIsDeveloperMode()) {
            $classFileCache = VAR_PATH . DS . self::CACHE_FOLDER . DS . self::CACHE_FILE;
            if (file_exists($classFileCache)) {
                include_once $classFileCache;
            }
            Zend_Loader_PluginLoader::setIncludeFileCache($classFileCache);
        }
    }
}
