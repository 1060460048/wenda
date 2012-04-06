<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
{
    protected function _initDefaultModuleAutoloader()
    {
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Wenda',
            'basePath'  => APPLICATION_PATH . '/modules/wenda',
        ));
    }
    
    protected function _initRoutes()
    {
        $this->bootstrap('frontController');
        $router = $this->frontController->getRouter();

        //catalog route
        $route = new Zend_Controller_Router_Route_Regex(
            'index/catalog-(\d+)-(.+)\.html',
            array(
                'controller' => 'index',
                'action'     => 'index',
                'module'     => 'wenda',
            ),
            array(
                1 => 'catalog',
                2 => 'show'
            ),
            'index/catalog-%d-%s.html'
        );
        $router->addRoute('catalogQuestion', $route);

        //show question link route
        $route = new Zend_Controller_Router_Route_Regex(
            'question/show-(.+)\.html',
            array(
                'controller'    => 'question',
                'action'        => 'show',
                'module'        => 'wenda',
            ),
            array(
                1   => 'token',

            ),
            'question/show-%s.html'
        );
        $router->addRoute('showQuestion', $route);

        // customer home route
        $route = new Zend_Controller_Router_Route(
            'home/:id',
            array(
                'action'        => 'home',
                'controller'    => 'customer',
                'module'        => 'wenda',
                'id'            => '',
            ),
            array(
                'id' => '[0-9]+'
            )
        );
        $router->addRoute('userHome', $route);	
        
        // question tag
        $route = new Zend_Controller_Router_Route(
            'question/tag/:tagname',
            array(
                'action'        => 'index',
                'controller'    => 'question',
                'module'        => 'wenda',            
            )
        );

        $router->addRoute('tagQuestion', $route);
    }
    
    protected function _initInstalled()
    {
        $baseUrl   = RFLib_Core::getBaseUrl();        
        if (!RFLib_Core::isInstalled()) {
            header('location: '.$baseUrl . 'install.php');
        } elseif (strpos(strtolower($_SERVER['REQUEST_URI']),'install')) {
            header('location: '.$baseUrl);
        }
    }	
}    
