<?php
class Wenda_CategoryController extends Zend_Controller_Action
{
    public function menuAction()
    {
        $categories = RFLib_Core::getModel('Category')->getCached('rootcategory')->getRoot();
        $firstCatId = isset($categories[0]) ? $categories[0]['id']: 1;
        
        $this->view->showCatTab     = true;
        $this->view->showAsk        = true;
        
        $controller = $this->_getParam('controller');
        $action     = $this->_getParam('action');
        
        if ($controller == 'customer') {
            $this->view->showCatTab = false;
        } elseif ($controller == 'question'){
            switch ($action) {
                case 'show' : 
                case 'index' :
                    $firstCatId  = null; 
                    break;
                case 'create' :
                    $this->view->selAsk = true;
                    $firstCatId  = null;                  
                    break;
            }
        }
        
        $this->view->selCatId  = $this->_getParam('catalog',$firstCatId);        
        $this->view->categories = $categories;
        $this->_helper->viewRenderer->setResponseSegment($this->_getParam('responseSegment')); 
    }
}