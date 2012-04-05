<?php
class Wenda_IndexController extends Zend_Controller_Action
{
    const PAGE_ROWS = 10;
    
    public function indexAction()
    {
        $categories = RFLib_Core::getModel('Category')->getCached('rootcategory')->getRoot();
        $firstCatId = isset($categories[0]) ? $categories[0]['id']: 1;
        $qModel     = RFLib_Core::getModel('Question');        
        $showTypes  = array('unsolve', 'solve', 'zero');
        $request    = $this->getRequest();
        
        $categoryId = $request->getParam('catalog', $firstCatId);
        $show       = $request->getParam('show', $showTypes[0]);
        $page       = $request->getParam('page', 1);

        switch ($show) {
            case 'solve' :
                $this->view->questions = $qModel->getCached($categoryId . $page)->solves($categoryId, $page, self::PAGE_ROWS);
                break;
            case 'zero' :
                $this->view->questions = $qModel->getCached($categoryId . $page)->zeros($categoryId, $page, self::PAGE_ROWS);
                break;
            case 'unsolve' :
            default :
                $this->view->questions = $qModel->getCached($categoryId . $page)->unsolves($categoryId, $page, self::PAGE_ROWS);
        }
        $this->view->selCatId    = $categoryId;
        $this->view->questionTab = $show;
        $this->view->hotKeywords = RFLib_Core::getModel('Keyword')->getCached($categoryId . 'keyword')->getHots($categoryId);
        $this->view->topUsers    = RFLib_Core::getModel('User')->getCached($categoryId . 'topusers')->getTopUsers($categoryId,50);
    }
}
