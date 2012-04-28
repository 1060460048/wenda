<?php
class Wenda_View_Helper_Showtags extends Zend_View_Helper_Abstract
{

    public function showTags($tags)
    {
        if (empty($tags)) {
            return null;
        }
        
        if (is_string($tags)) {
        	$tags = $this->view->escape($tags);
            $tags = explode(' ', $tags);
        }

        foreach ($tags as $tag) {
            echo $this->view->linkTo($tag, '@tagQuestion?tagname=' . $tag, 'class=tag') . '&nbsp;';
        }
    }
}
?>