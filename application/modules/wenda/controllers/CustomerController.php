<?php

class Wenda_CustomerController extends Zend_Controller_Action
{

    protected $_model;
    protected $_authService;

    public function init()
    {
        //get model and service
        $this->_model = RFLib_Core::getModel('User');
        $this->_authService = RFLib_Core::getService('Authentication');

        unset($this->view->errorMsg);
    }

    public function registerAction()
    {

    }

    public function registrationAction()
    {
        $form = RFLib_Core::getForm('userRegister');
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->render('register');
        } else {
            $postData = $request->getPost();
            if (!$form->isValid($postData)) {
                $errors = array();
                foreach ($form->getErrors() as $field => $error) {
                    if (count($error) > 0) {
                        $element = $form->getElement($field);
                        $errors[] = '['.$element->getLabel().'] ' . implode(';', $element->getMessages());
                    }
                }
                $this->view->errorMsg = $errors;
                $this->view->postData = $postData;
                return $this->render('register');
            } elseif( false === $this->_model->register($postData)) {
                return $this->render('register');
            }
        }

        $this->_authService->authenticate($postData);
        $this->_redirect('index');
    }

    public function loginAction()
    {

    }

    public function authenticateAction()
    {
        $loginErrorMsg = array('登录失败，请确认是否输入正确的邮箱地址和密码!');
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Validate by form
        $postData = $request->getPost();
        $form = RFLib_Core::getForm('userLogin');
        if (!$form->isValid($postData)) {
            $this->view->errorMsg = $loginErrorMsg;
            return $this->render('login');
        }

        // validate by tabledb
        if (false === $this->_authService->authenticate($form->getValues())) {
            $this->view->errorMsg = $loginErrorMsg;
            return $this->render('login');
        }

        return $this->_redirect("index");
    }

    public function logoutAction()
    {
        $this->_authService->logout();
        return $this->_redirect('index');
    }

    public function homeAction()
    {
        $userid = $this->getRequest()->getParam('id');
        Zend_Debug::dump($userid);
    }

}