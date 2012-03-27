<?php
class Wenda_IndexController extends RFLib_Controller_Action
{
    const PAGE_ROWS = 10;
    
    public function indexAction()
    {
        $categories = RFLib_Core::getModel('Category')->getCached()->getRoot();
        $firstCatId = isset($categories[0]) ? $categories[0]['id']: 1;
        $qModel     = RFLib_Core::getModel('Question');        
        $showTypes  = array('unsolve', 'solve', 'zero');
        $request    = $this->getRequest();
        
        $categoryId = $request->getParam('catalog', $firstCatId);
        $show       = $request->getParam('show', $showTypes[0]);
        $page       = $request->getParam('page', 1);

        switch ($show) {
            case 'solve' :
                $this->view->questions = $qModel->solves($categoryId,$page,self::PAGE_ROWS);
                break;
            case 'zero' :
                $this->view->questions = $qModel->zeros($categoryId,$page,self::PAGE_ROWS);
                break;
            case 'unsolve' :
            default :
                $this->view->questions = $qModel->unsolves($categoryId,$page,self::PAGE_ROWS);
        }
        $this->view->selCatId    = $categoryId;
        $this->view->questionTab = $show;
        $this->view->hotKeywords = RFLib_Core::getModel('Keyword')->getHots($categoryId);
    }
}
