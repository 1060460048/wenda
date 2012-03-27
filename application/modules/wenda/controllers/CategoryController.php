<?php
class Wenda_CategoryController extends RFLib_Controller_Action
{
    public function menuAction()
    {
        $categories = RFLib_Core::getModel('Category')->getCached()->getRoot();
        $firstCatId = isset($categories[0]) ? $categories[0]['id']: 1;
        
        $this->view->showCatTab = true;
        switch ($this->_getParam('action')) {
            case 'ask':
                $firstCatId = null;
                break;
            case 'login':
            case 'register':
                $this->view->showCatTab = false;
                break;
        }
        
        $this->view->selCatId = $this->_getParam('catalog', $firstCatId);
        $this->view->categories = $categories;
        $this->_helper->viewRenderer->setResponseSegment($this->_getParam('responseSegment'));        
    }
}