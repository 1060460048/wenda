<?php

class Wenda_View_Helper_Replies extends Zend_View_Helper_HtmlElement
{

    public function replies($replies)
    {
        if (null === $replies) {
            return;
        }
        $html = '';
        foreach ($replies as $reply) {
            $html .= '<dd id="post_answer_' . $reply['id'] . '">';
            $html .= $this->view->face($reply['user_logo'], $reply['user_id']);
            $html .= '<div class="p">';
            $html .= '  <span class="c">' . strip_tags($reply['content']) . '</span>';
            $html .= '  <span class="t">' . $this->view->timing($reply['created_at']) . ' by '.$reply['user_name'] . '</span>';
            $html .= '  <span class="o"><a href="javascript:reply_to_post(172975,106025)">回复</a></span>';
            $html .= '</div>';
            $html .= '<div class="clearfix"></div></dd>';
        }
        return '<strong>--- 共有 ' . count($replies) . ' 条评论 ---</strong><dl>'.$html.'</dl>';
    }
}