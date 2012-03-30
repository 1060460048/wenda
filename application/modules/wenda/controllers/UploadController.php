<?php
class Wenda_UploadController extends Zend_Controller_Action
{
    public function imageAction()
    {
        if ($this->getRequest()->isPost()){
            $upload = new Zend_File_Transfer_Adapter_Http();        
            $dirs = array(
                'image' => array('gif', 'jpg', 'jpeg', 'png'),
                'flash' => array('swf', 'flv'),
            );
            $dir = $this->_getParam('dir');
            if (!isset($dirs[$dir])) {
                 $this->_alert('上传类型出错!');
            }
            $savePath = UPLOAD_PATH . DS . $dir;
            
            //检查文件大小
            $upload->addValidator('Size', false, 500000);
            if (false == $upload->isValid()) {
                $this->_alert('上传文件大小超过500KB限制。');
            }
            //检查目录
            if (@is_dir($savePath) === false) {
                $this->_alert('上传目录不存在。');
            }
            //检查目录写权限
            if (@is_writable($savePath) === false) {
                $this->_alert('上传目录没有写权限。');
            }
            //获得文件类型
            $upload->addValidator('Extension', false, $dirs[$dir]);
            if (false == $upload->isValid()) {
                $this->_alert('只能上传'. implode('、',$dirs[$dir] ).'文件类型');
            }
            //设置保存的Path
            $upload->setDestination($savePath);
            //设置新的文件名
            $fileInfo = $upload->getFileInfo();
            $tmpFile = $fileInfo['imgFile']['name'];
            $extension = explode('.', $tmpFile);
            $extension = array_pop($extension);
            $newFile =  md5($tmpFile . uniqid()) . '.' . $extension;
            $upload->addFilter('Rename', array('target' => $savePath . DS . $newFile, 'overwrite' => true));            
            //保存文件    
            $upload->receive();
            //返回文件url
            echo Zend_Json::encode(array('error' => 0, 'url' => $this->view->baseUrl().'/uploads/image/' . $newFile));
            exit;        
        } else {
            $this->_alert('请选择文件。');
            exit;
        }
    }
   
    protected function _alert($msg)
    {
        header('Content-type: text/html; charset=UTF-8');
        echo Zend_Json::encode(array('error' => 1, 'message' => $msg));
        exit;
    }
}