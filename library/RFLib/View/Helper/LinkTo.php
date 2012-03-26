<?php
/**
 * A tag helper
 * 
 * @example    	linkTo(text,url)
 *       		linkTo(text,'controller/action?param=value')
 *				linkTo(text,'@routeName?param=value') *
 * @copyright	Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license     New BSD License
 */
class RFLib_View_Helper_Linkto extends Zend_View_Helper_HtmlElement
{
    public function linkTo($text, $path, $attribs = null)
    {
        if ('#' == $path) {
            $url = 'javascript:void(0);';
        } else if ('homepage' == $path) {
            $url = $this->view->baseUrl();
            $url = empty($url) ? '/' : $url;
        } else {
            $url = $this->view->urlFor($path);
        }

        if (null === $attribs) {
            $options = '';
        } else {
            $options = $this->_htmlAttribs(
                RFLib_Utility_Parse::attribToArray($attribs)
            );
        }
        return '<a href="' . $url . '" ' . $options . '>' . $text . '</a>';
    }
}