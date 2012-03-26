<?php
/**
 * Image tag helper
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
 */
class RFLib_View_Helper_Imagetag extends Zend_View_Helper_HtmlElement
{
    /**
     * 生成img标签
     * 
     * @example imageTag('imageFile','size=64x48 class=red')
     * @example imageTag('imageFile')
     * 
     * @param string $src
     * @param string $attribs
     * @return string
     */
    public function imageTag($src, $attribs = null)
    {
        $url = $this->view->skinUrl . '/images/' . $src;

        if (null === $attribs) {
            $options = '';
        } else {
            $arr = RFLib_Utility_Parse::attribToArray($attribs);
            if (isset($arr['size'])) {
                list($arr['width'], $arr['height']) = explode('x', $arr['size'], 2);
                unset($arr['size']);
            }
            $options = $this->_htmlAttribs($arr);
        }
        
        return '<img src="' . $url .'"'. $options . ' />';
    }
}
?>
