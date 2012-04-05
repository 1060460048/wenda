<?php
class Wenda_IndexController extends Zend_Controller_Action
{
    const PAGE_ROWS = 10;
public function init()
{
         $frontendOptions = array(
         'lifetime' => 3600, // 缓存寿命
         'debug_header' => true, // true是打开debug，通常设为false

         'regexps' => array(
                        '^/$' => array('cache' => true), // 所有页面都缓存
                        '^/category/menu' => array('cache' => false), //仅缓存所需页面
                     ),

         'default_options' => array(
            'cache_with_get_variables' => true,
            'cache_with_post_variables' => true,
            'make_id_with_cookie_variables' => true, // 注意如果开了session要把这个打开
            'cache_with_session_variables' => true, // 注意如果开了session要把这个打开
            'cache_with_files_variables' => true,
            'cache_with_cookie_variables' => true, // 注意如果开了session要把这个打开
         )
       );

      $backendOptions = array(
         'cache_dir' => VAR_PATH . '/cache/', // 缓存存放路径，必须存在并可写
      );

      $cache = Zend_Cache::factory('Page', 
                                 'File', 
                                 $frontendOptions, 
                                 $backendOptions);
      $cache->start(); // 开始缓存
}    
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
