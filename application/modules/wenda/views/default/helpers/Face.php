<?php
class Wenda_View_Helper_Face extends Zend_View_Helper_HtmlElement
{

    public function face($src=null, $userId=null, $attribs = null)
    {
        if (null === $src) {
            if ($this->view->authInfo()->isLogined()) {
                $src = $this->view->authInfo()->getLogo();
                $userId = $this->view->authInfo()->getId();
            } else {
                $src = 'portrait.gif';
            }
        }
        $url = $this->view->baseUrl() . '/upload/user/' . $src;
        $attribs = empty($attribs) ? 'class=user target=_blank' : $attribs;

        if (null === $userId) {
            return '<img src="'.$url.'" />';
        } else {
            return $this->view->linkTo(
                    '<img src="' . $url . '" />', '@userHome?id=' . $userId, $attribs
            );
        }
    }
}

?>