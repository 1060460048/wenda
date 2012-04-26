<?php

class Wenda_SearchController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $request = $this->getRequest();
        $scope = $request->getParam('scope','bbs');
        $query = $request->getParam('q');
        $page = $request->getParam('page',1);
        
        $results = RFLib_Core::getModel('Question')
                        ->getCached(str_replace(' ','',$query . $page))
                        ->getBySearchQuery($query, $page, 50);

        $words = explode(' ',$query);
        $patterns = array();
        $replacements = array();
        foreach($words as $i=>$word) {
            $patterns[] = '/' . preg_quote($word) . '/i';
            $replacements[] = '<span class="highlight">\\0</span>';
        }
        
        $arr = array();
        $i=0;
        foreach($results as $row) {
            $arr[$i] = $row;
            $arr[$i]['subject'] = preg_replace($patterns,$replacements, $arr[$i]['subject']); 
            $arr[$i]['content'] = preg_replace($patterns,$replacements, $arr[$i]['content']); 
            $i++;
        }
        $this->view->results = $arr;                
    }

}