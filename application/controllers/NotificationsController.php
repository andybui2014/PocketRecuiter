<?php

class NotificationsController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        if(empty($client))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    
    public function listAction()
    {
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $clientID =  $sestionClient['ClientID'];
        $notiClass = new PR_Api_Core_NotiClass();
        $result = $notiClass->getList($clientID);
       // $result = $result1[0];
        //$notiID = $result['NotiID'];
       // $noti = new PR_Api_Noti($notiID);
       // $result = $noti->getInfo();

           //     echo "<pre>";
         //print_r($result1);
        // echo "</pre>";die();
        $this->view->result = $result; //UserName
        $this->view->clientIDLogin = $clientID;
        $this->view->userName = $sestionClient['UserName'];
    }

    public function saveNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $content = $request->getParam("textnotification", "");
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $clientID =  $sestionClient['ClientID'];
        $noti = new PR_Api_Noti(null);
        $updateFields = array('NotiDesc'=>$content,'lmbClientID'=>$clientID,'cbClientID'=>$clientID);
        $result = $noti->save($updateFields);
        if($result){
            $return = array("success" => 1, "error" => "");
        } else{
            $return = array("success" => 0, "error" => "");
        }
        //echo "<pre>";
        // print_r($content);
        // echo "</pre>";die();


        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }

    public function saveEditNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $content = $request->getParam("editNotification", "");
        $ModalEditNotiID = $request->getParam("ModalEditNotiID", "");
        //echo "<pre>";
       // print_r($content); echo "<br>"; echo $ModalEditNotiID;
       //echo "</pre>";die();
        //$notiID = 1;
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $clientID =  $sestionClient['ClientID'];
        $noti = new PR_Api_Noti($ModalEditNotiID);
        $updateFields = array('NotiDesc'=>$content,'lmbClientID'=>$clientID);
        $result = $noti->save($updateFields);
        if($result){
            $return = array("success" => 1, "error" => "");
        } else{
            $return = array("success" => 0, "error" => "");
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }

    public function deleteNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $listNotiID = $request->getParam("listNotiID", "");
        $core = new PR_Api_Core_NotiClass();
        $return = $core->delete($listNotiID);
        if(empty($return)){
            $return = array("success" => 1, "error" => "");
        }else{
            $return = array("success" => 0, "error" => "");
        }
        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }
}
