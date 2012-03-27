<?php
/**
 * Wenda_View_Helper_Breadcrumb
 * 
 * Display the category breadcrumb
 * 
 * @category   Wenda
 * @package    Wenda_View_Helper
 * @copyright  Copyright (c) 2011 Ricky Feng (ricky.feng@163.com)
 * @license    New BSD License
 */
class Wenda_View_Helper_Breadcrumb extends Zend_View_Helper_Abstract 
{       
    public function breadcrumb($product = null)
    {
        if ($this->view->bread) {
            $bread = $this->view->bread;
            $crumbs = array();
            $bread = array_reverse($bread);
            
            $crumbs[] = $this->view->linkTo($this->view->siteName, 'homepage');
            foreach ($bread as $category) {
                $crumbs[] = $this->view->linkTo(
                                $category['title'], 
                                '@catalogQuestion?catalog=' . $category['id'].'&show=unsolve');
            }
            
            if (null !== $product) {
                $crumbs[] = $this->view->Escape($product);
            }
            
            return '当前位置：' . join(' &raquo; ', $crumbs);
        } else {
            return '当前位置：' . $this->view->linkTo($this->view->siteName, 'homepage');
        }
        
    }    
}
