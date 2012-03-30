<?php

class Wenda_AjaxController extends Zend_Controller_Action
{

    public function replyAction()
    {
        $request = $this->getRequest();
        $postId = $request->getParam('postid');
        if (!isset($postId)) {
            echo '数据传送错误！';
            exit;
        }
        $userName = RFLib_Core::getModel('Answer')->getUserNameById($postId);
        $url = $this->view->urlFor('question/reply');
        
        if (!empty($userName)) {        
            $replayForm = <<<EOD
<div id="ajaxbox">
    <h2 id="title">评论“{$userName}”的答案</h2>
    <div id="content">
        <div style="display:none" class="poperror-msg" id="s_error_msg"></div>
        <div style="display:none" class="popsuccess-msg" id="s_success_msg"></div>
        <form method="post" action="question/reply" id="frm_q_detail">
            <input type="hidden" value="{$postId}" name="parent_id" id="parent_id" />
            <div>请输入评论内容：</div>
            <div><textarea style="width: 400px; height: 120px;" id="rp_content" name="rp_content"></textarea></div>
            <div><input type="button" onclick="post_reply('{$url}');" id="bt_submit_new_reply" value="发表评论"/>
            <a href="javascript:closeit()">取消</a></div>
        </form>
    </div>
</div>
EOD;
        echo $replayForm;
        }
        exit;
    }

}