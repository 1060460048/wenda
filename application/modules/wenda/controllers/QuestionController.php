<?php

class Wenda_QuestionController extends Zend_Controller_Action
{
    protected $_model;
    protected $_flashMessenger = null;

    public function init()
    {
        parent::init();

        $this->_model = RFLib_Core::getModel('Question');
        $jsPath = $this->view->baseUrl() . '/js';

        //add kideditor js
        $this->view->addJavascript('kindeditor/kindeditor-min.js');
        $this->view->addJavascript('wenda/qcontent.js');

        //add syntax highlighter js
        $this->view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shCore.css');
        $this->view->headLink()->appendStylesheet($jsPath . '/syntax-highlighter/styles/shThemeDefault.css');
        $this->view->addJavascript('syntax-highlighter/brush.js');
        $this->view->headScript()->offsetSetScript(9999,
            "SyntaxHighlighter.config.clipboardSwf = '". $this->view->baseUrl()."/js/syntax-highlighter/scripts/clipboard.swf';" .
            " SyntaxHighlighter.all();"
        );

        //create flash manager
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function showAction()
    {
        $request = $this->getRequest();
        $token = $request->getParam('token');
        $page = $request->getParam('page',1);

        $qDetail = RFLib_Core::getModel('Question')->getDetailByToken($token);
        if (empty($qDetail)) {
            $this->_redirect('index');
        }
        
        $roots =  RFLib_Core::getModel('Answer')->getByQuestionId($qDetail['id'] , $page, 10);
        $arr = array();
        foreach($roots as $row) {
            $arr[] = $row->id;
        }
        $this->view->answersPaginator = $roots;
        $this->view->answers = RFLib_Core::getModel('Answer')->getAllByIds($arr);
        $this->view->question = $qDetail;

        $messages = $this->_flashMessenger->getMessages();
        $this->view->errorMsg = isset($messages[0]) ? $messages[0] : null;
        $this->view->postData = isset($messages[1]) ? $messages[1] : null;
    }

    public function askAction()
    {
        $this->view->categories = RFLib_Core::getModel('Category')->getCached()->getRoot();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $form = RFLib_Core::getForm('questionCreate');
            if (!$form->isValid($postData)) {
                $this->view->errorMsg = $this->_getFormErrors($form);
                $this->view->postData = $postData;
            } elseif (false === ($questionId = $this->_model->create($postData))) {
                $this->view->errorMsg = array('提交数据失败!');
                $this->view->postData = $postData;
            } else {
                $redirector = $this->_helper->getHelper('Redirector');
                $goto = array(
                    'urlOptions' => array(
                        'controller' => 'question',
                        'action' => 'asked',
                        'id' => $questionId,
                    ),
                );
                return $redirector->gotoRoute($goto['urlOptions']);
            }
        }
    }

    public function askedAction()
    {
        $request = $this->getRequest();
        $questionId = $request->getParam('id');
        if (isset($questionId)) {
            $this->view->question = RFLib_Core::getModel('Question')->getShortDataById($questionId);
        }
    }

    public function answerAction()
    {
        $auth = RFlib_Core::getService('Authentication');
        if (!$auth->getIdentity()) {
            $this->_redirect('index');
        }

        //when user answer question and submit form
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $form = RFLib_Core::getForm('questionAnswer');
            if (!$form->isValid($postData)) {
                $this->_flashMessenger->addMessage($this->_getFormErrors($form));
                $this->_flashMessenger->addMessage($postData);
            } else {
                $answerModel = RFLib_Core::getModel('Answer');
                if (!$answerModel->create($postData,$form)) {
                    $this->_flashMessenger->addMessage(array('保存数据失败'));
                    $this->_flashMessenger->addMessage($postData);
                }
            }

            $redirector = $this->_helper->getHelper('Redirector');
            $goto = array(
                'urlOptions' => array(
                    'controller' => 'question',
                    'action' => 'show',
                    'token' => $request->getParam('token'),
                ),
                'route' => 'showQuestion'
            );

            return $redirector->gotoRoute($goto['urlOptions'], $goto['route']);
        }
        $this->_redirect('index');
    }

    public function replyAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $form = RFLib_Core::getForm('questionReply');
            if ($form->isValid($postData)) {
                $answerModel = RFLib_Core::getModel('Answer');
                $postData['content'] = $postData['rp_content'];
                $postData['question_id'] = $answerModel->getQuestionIdById($postData['parent_id']);
                if ($answerModel->create($postData,$form)) {
                    $json = array('error'=>0,'msg'=>'','url'=>$this->view->urlFor('question/postreply?id='.$postData['parent_id']));
                } else {
                    $json = array('error'=>1,'msg'=>'保存数据失败!');
                }
            } else {
                $json = array('error'=>2, 'msg'=>'评论内容不能为空');
            }
            echo Zend_Json::encode($json);
            exit;
        }
    }

    public function postreplyAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $replies = RFLib_Core::getModel('Answer')->getRepliesById($id);
        echo $this->view->replies($replies);
        exit;
    }

    protected function _getFormErrors($form)
    {
        $errors = array();
        foreach ($form->getErrors() as $field => $error) {
            if (count($error) > 0) {
                $element = $form->getElement($field);
                $errors[] = '['.$element->getLabel().'] '.implode(';', $element->getMessages());
            }
        }
        return $errors;
    }
}